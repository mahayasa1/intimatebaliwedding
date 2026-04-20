<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    // =========================================================================
    // KONFIGURASI — sesuaikan sesuai kebutuhan
    // =========================================================================

    /** Lebar maksimum gambar utama (px) */
    const MAX_WIDTH = 1280;

    /** Lebar maksimum thumbnail (px) */
    const THUMB_WIDTH = 640;

    /** Kualitas WebP gambar utama (0–100) */
    const QUALITY_MAIN = 75;

    /** Kualitas WebP thumbnail (0–100) */
    const QUALITY_THUMB = 65;

    /** Skip kompresi jika gambar sudah kecil dari lebar ini */
    const SKIP_COMPRESS_WIDTH = 200;

    /** Maksimum ukuran file input (bytes). Tolak sebelum proses GD */
    const MAX_INPUT_BYTES = 20 * 1024 * 1024; // 20 MB

    // =========================================================================
    // PUBLIC API
    // =========================================================================

    /**
     * Kompres & convert gambar ke WebP. Gantikan file asli.
     * Kembalikan path baru (bisa berubah ekstensi ke .webp).
     */
    public static function compress(string $storagePath): string
    {
        if (!extension_loaded('gd')) {
            return $storagePath;
        }

        $fullPath = Storage::disk('public')->path($storagePath);

        if (!file_exists($fullPath)) {
            return $storagePath;
        }

        // Tolak file terlalu besar
        if (filesize($fullPath) > self::MAX_INPUT_BYTES) {
            \Illuminate\Support\Facades\Log::warning("[ImageHelper] File terlalu besar: {$storagePath}");
            return $storagePath;
        }

        $info = @getimagesize($fullPath);
        if (!$info) {
            return $storagePath;
        }

        [$origW, $origH, $type] = $info;

        // Sudah sangat kecil, skip
        if ($origW <= self::SKIP_COMPRESS_WIDTH) {
            return $storagePath;
        }

        $src = self::createSource($fullPath, $type);
        if (!$src) {
            return $storagePath;
        }

        [$newW, $newH] = self::calcDimensions($origW, $origH, self::MAX_WIDTH);

        $canvas = self::createCanvas($newW, $newH, $type, $src);
        imagecopyresampled($canvas, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        $webpPath = self::toWebpPath($storagePath);
        $webpFull = Storage::disk('public')->path($webpPath);

        self::ensureDir(dirname($webpFull));
        imagewebp($canvas, $webpFull, self::QUALITY_MAIN);
        imagedestroy($canvas);

        // Hapus file asli jika ekstensi berbeda
        if ($storagePath !== $webpPath && file_exists($fullPath)) {
            @unlink($fullPath);
        }

        return $webpPath;
    }

    /**
     * Buat thumbnail WebP.
     */
    public static function createThumbnail(
        string $storagePath,
        int $width   = self::THUMB_WIDTH,
        int $quality = self::QUALITY_THUMB
    ): ?string {
        if (!extension_loaded('gd')) {
            return $storagePath;
        }

        $fullPath = Storage::disk('public')->path($storagePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        $info = @getimagesize($fullPath);
        if (!$info) {
            return null;
        }

        [$origW, $origH, $type] = $info;

        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);
        $thumbRelPath = 'thumbs/' . ($dir !== '.' ? $dir . '/' : '') . $filename . '.webp';
        $thumbFullPath = Storage::disk('public')->path($thumbRelPath);

        // Jika thumbnail sudah ada dan lebih baru dari sumber, skip
        if (file_exists($thumbFullPath) && filemtime($thumbFullPath) >= filemtime($fullPath)) {
            return $thumbRelPath;
        }

        self::ensureDir(dirname($thumbFullPath));

        if ($origW <= $width) {
            $src = self::createSource($fullPath, $type);
            if (!$src) {
                return $storagePath;
            }
            imagewebp($src, $thumbFullPath, $quality);
            imagedestroy($src);
            return $thumbRelPath;
        }

        [$newW, $newH] = self::calcDimensions($origW, $origH, $width);
        $src   = self::createSource($fullPath, $type);
        if (!$src) {
            return null;
        }

        $thumb = self::createCanvas($newW, $newH, $type, $src);
        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        imagewebp($thumb, $thumbFullPath, $quality);
        imagedestroy($thumb);

        return $thumbRelPath;
    }

    /**
     * Upload, kompres, buat thumbnail. Gunakan di controller.
     *
     * @return array{path: string, thumb: string}
     */
    public static function storeAndCompress($file, string $directory): array
    {
        $originalPath   = $file->store($directory, 'public');
        $compressedPath = self::compress($originalPath);
        $thumbPath      = self::createThumbnail($compressedPath) ?? $compressedPath;

        return [
            'path'  => $compressedPath,
            'thumb' => $thumbPath,
        ];
    }

    /**
     * Ambil URL thumbnail. Jika tidak ada, kembalikan URL original.
     * Mengembalikan URL lengkap (bukan path relatif).
     */
    public static function thumbUrl(string $storagePath): string
    {
        $thumbPath = self::thumb($storagePath);
        return asset('storage/' . $thumbPath);
    }

    /**
     * Ambil path thumbnail. Jika tidak ada, kembalikan path original.
     */
    public static function thumb(string $storagePath): string
    {
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);
        $thumbPath = 'thumbs/' . ($dir !== '.' ? $dir . '/' : '') . $filename . '.webp';

        return Storage::disk('public')->exists($thumbPath) ? $thumbPath : $storagePath;
    }

    /**
     * Hapus thumbnail terkait.
     */
    public static function deleteThumb(string $storagePath): void
    {
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);
        $thumbPath = 'thumbs/' . ($dir !== '.' ? $dir . '/' : '') . $filename . '.webp';

        if (Storage::disk('public')->exists($thumbPath)) {
            Storage::disk('public')->delete($thumbPath);
        }
    }

    /**
     * Hapus gambar + thumbnail.
     */
    public static function delete(string $storagePath): void
    {
        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
        self::deleteThumb($storagePath);
    }

    // =========================================================================
    // HELPERS INTERNAL
    // =========================================================================

    private static function createSource(string $fullPath, int $type)
    {
        return match ($type) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($fullPath),
            IMAGETYPE_PNG  => @imagecreatefrompng($fullPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($fullPath),
            IMAGETYPE_GIF  => @imagecreatefromgif($fullPath),
            default        => null,
        };
    }

    private static function createCanvas(int $w, int $h, int $type, $src)
    {
        $canvas = imagecreatetruecolor($w, $h);

        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $w, $h, $transparent);
        } else {
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefilledrectangle($canvas, 0, 0, $w, $h, $white);
        }

        return $canvas;
    }

    private static function calcDimensions(int $origW, int $origH, int $maxW): array
    {
        if ($origW <= $maxW) {
            return [$origW, $origH];
        }
        $ratio = $maxW / $origW;
        return [$maxW, (int) round($origH * $ratio)];
    }

    private static function toWebpPath(string $storagePath): string
    {
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);
        return ($dir && $dir !== '.') ? "{$dir}/{$filename}.webp" : "{$filename}.webp";
    }

    private static function ensureDir(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}