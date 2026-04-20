<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    // =========================================================================
    // KONFIGURASI KOMPRESI
    // =========================================================================

    /** Lebar maksimum gambar utama (px) */
    const MAX_WIDTH = 1280;

    /** Lebar maksimum thumbnail (px) */
    const THUMB_WIDTH = 640;

    /** Kualitas WebP untuk gambar utama (0–100) */
    const QUALITY_MAIN = 72;

    /** Kualitas WebP untuk thumbnail (0–100) */
    const QUALITY_THUMB = 65;

    /** Threshold lebar agar gambar di-skip kompresi (sudah cukup kecil) */
    const SKIP_COMPRESS_WIDTH = 200;

    // =========================================================================
    // PUBLIC API
    // =========================================================================

    /**
     * Kompres & convert gambar yang sudah tersimpan di storage ke WebP.
     * Menggantikan file asli dengan versi yang sudah dikompres.
     * Mengembalikan path baru (bisa berubah ekstensi ke .webp).
     *
     * @param  string  $storagePath  Path relatif di disk 'public'
     * @return string                Path setelah kompresi
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

        $info = @getimagesize($fullPath);
        if (!$info) {
            return $storagePath;
        }

        [$origW, $origH, $type] = $info;

        // Gambar sudah sangat kecil → skip
        if ($origW <= self::SKIP_COMPRESS_WIDTH) {
            return $storagePath;
        }

        $src = self::createSource($fullPath, $type);
        if (!$src) {
            return $storagePath;
        }

        // Hitung dimensi baru
        [$newW, $newH] = self::calcDimensions($origW, $origH, self::MAX_WIDTH);

        $canvas = self::createCanvas($newW, $newH, $type, $src);
        imagecopyresampled($canvas, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        // Simpan sebagai WebP, ganti file lama
        $webpPath = self::toWebpPath($storagePath);
        $webpFull = Storage::disk('public')->path($webpPath);

        self::ensureDir(dirname($webpFull));
        imagewebp($canvas, $webpFull, self::QUALITY_MAIN);
        imagedestroy($canvas);

        // Hapus file asli jika berbeda path
        if ($storagePath !== $webpPath && file_exists($fullPath)) {
            @unlink($fullPath);
        }

        return $webpPath;
    }

    /**
     * Buat thumbnail WebP dari file yang sudah tersimpan di storage.
     * Juga mengkompresi agresif untuk performa halaman.
     *
     * @param  string  $storagePath  Path relatif di disk 'public'
     * @param  int     $width        Lebar thumbnail (default THUMB_WIDTH)
     * @param  int     $quality      Kualitas WebP (default QUALITY_THUMB)
     * @return string|null           Path thumbnail (relatif) atau null jika gagal
     */
    public static function createThumbnail(
        string $storagePath,
        int $width = self::THUMB_WIDTH,
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

        // Susun path thumbnail
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);
        $thumbRelPath = 'thumbs/' . ($dir !== '.' ? $dir . '/' : '') . $filename . '.webp';
        $thumbFullPath = Storage::disk('public')->path($thumbRelPath);

        self::ensureDir(dirname($thumbFullPath));

        // Gambar sudah lebih kecil dari target → simpan WebP langsung (masih kompres quality)
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

        $src    = self::createSource($fullPath, $type);
        if (!$src) {
            return null;
        }

        $thumb  = self::createCanvas($newW, $newH, $type, $src);
        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        imagewebp($thumb, $thumbFullPath, $quality);
        imagedestroy($thumb);

        return $thumbRelPath;
    }

    /**
     * Upload & langsung kompres + buat thumbnail.
     * Gunakan method ini di controller sebagai pengganti $request->file->store().
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string                          $directory  Direktori di disk 'public'
     * @return array{path: string, thumb: string}
     */
    public static function storeAndCompress($file, string $directory): array
    {
        // Simpan file asli dulu
        $originalPath = $file->store($directory, 'public');

        // Kompres → konversi ke WebP
        $compressedPath = self::compress($originalPath);

        // Buat thumbnail
        $thumbPath = self::createThumbnail($compressedPath) ?? $compressedPath;

        return [
            'path'  => $compressedPath,
            'thumb' => $thumbPath,
        ];
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
     * Hapus thumbnail terkait sebuah file.
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
     * Hapus gambar (original + thumbnail).
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

        // Pertahankan transparansi PNG/GIF
        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $w, $h, $transparent);
        } else {
            // Isi background putih untuk JPEG
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