<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * ImageHelper v2.0
 * ============================================================
 * Server-side image processing untuk Laravel.
 *
 * Alur kerja:
 *  1. Client upload gambar yang SUDAH dikompresi oleh image-compressor.js
 *  2. Server compress lagi sebagai safety net (jika client tidak support)
 *  3. Simpan dalam format WebP
 *  4. Buat thumbnail otomatis
 *
 * Metode publik:
 *  - compress(storagePath)                  → kompres gambar, return path baru
 *  - createThumbnail(storagePath, w, q)     → buat thumbnail WebP
 *  - storeAndCompress(file, directory)      → upload + compress + thumbnail
 *  - thumb(storagePath)                     → ambil path thumbnail (fallback ke original)
 *  - thumbUrl(storagePath)                  → ambil URL thumbnail
 *  - delete(storagePath)                    → hapus gambar + thumbnail
 *  - deleteThumb(storagePath)               → hapus thumbnail saja
 * ============================================================
 */
class ImageHelper
{
    /* ─── KONFIGURASI ──────────────────────────────────────── */

    /** Lebar maksimum gambar utama (px) */
    const MAX_WIDTH = 1280;

    /** Tinggi maksimum gambar utama (px) */
    const MAX_HEIGHT = 1280;

    /** Lebar maksimum thumbnail (px) */
    const THUMB_WIDTH = 640;

    /** Kualitas WebP gambar utama (0–100) */
    const QUALITY_MAIN = 82;

    /** Kualitas WebP thumbnail (0–100) */
    const QUALITY_THUMB = 72;

    /**
     * Skip kompresi jika gambar LEBIH KECIL dari lebar ini (px).
     * Gambar sangat kecil tidak perlu diproses ulang.
     */
    const SKIP_COMPRESS_WIDTH = 150;

    /**
     * Ukuran maksimum file input yang diterima (bytes).
     * File lebih besar dari ini langsung ditolak sebelum diproses GD.
     * Diset 30MB sebagai safety — Cloudflare max 100MB.
     */
    const MAX_INPUT_BYTES = 30 * 1024 * 1024; // 30 MB

    /**
     * Batas ukuran RAM untuk GD image resource (bytes).
     * Gambar dengan perkiraan RAM > nilai ini akan diresize lebih agresif.
     */
    const MAX_GD_RAM_BYTES = 128 * 1024 * 1024; // 128 MB

    /* ─── PUBLIC API ───────────────────────────────────────── */

    /**
     * Kompres & konversi gambar ke WebP.
     * Menggantikan file asli dengan versi terkompresi.
     *
     * @param  string $storagePath  Path relatif di disk 'public'
     * @return string               Path hasil (bisa berubah ekstensi ke .webp)
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
        $fileSize = filesize($fullPath);
        if ($fileSize > self::MAX_INPUT_BYTES) {
            Log::warning("[ImageHelper] File terlalu besar ($fileSize bytes): {$storagePath}");
            return $storagePath;
        }

        $info = @getimagesize($fullPath);
        if (!$info) {
            return $storagePath;
        }

        [$origW, $origH, $type] = $info;

        // Skip gambar sangat kecil
        if ($origW <= self::SKIP_COMPRESS_WIDTH) {
            return $storagePath;
        }

        // Hitung dimensi baru
        [$newW, $newH] = self::calcDimensions($origW, $origH, self::MAX_WIDTH, self::MAX_HEIGHT);

        // Estimasi RAM yang dibutuhkan GD
        $estimatedRam = $newW * $newH * 4; // 4 bytes per pixel (RGBA)
        if ($estimatedRam > self::MAX_GD_RAM_BYTES) {
            // Kurangi dimensi lebih lanjut agar muat di RAM
            $scale = sqrt(self::MAX_GD_RAM_BYTES / $estimatedRam) * 0.9;
            $newW  = (int) round($newW * $scale);
            $newH  = (int) round($newH * $scale);
            Log::info("[ImageHelper] Dimensi dikurangi ke {$newW}x{$newH} untuk efisiensi RAM.");
        }

        // Naikkan memory_limit sementara untuk gambar besar
        $originalMemoryLimit = ini_get('memory_limit');
        $neededMB = max(128, ceil(($newW * $newH * 4 * 2.5) / 1024 / 1024));
        ini_set('memory_limit', $neededMB . 'M');

        $src = self::createSource($fullPath, $type);

        // Restore memory_limit
        ini_set('memory_limit', $originalMemoryLimit);

        if (!$src) {
            return $storagePath;
        }

        $canvas = self::createCanvas($newW, $newH, $type);
        imagecopyresampled($canvas, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        $webpPath = self::toWebpPath($storagePath);
        $webpFull = Storage::disk('public')->path($webpPath);

        self::ensureDir(dirname($webpFull));

        $saved = imagewebp($canvas, $webpFull, self::QUALITY_MAIN);
        imagedestroy($canvas);

        if (!$saved) {
            Log::error("[ImageHelper] Gagal menyimpan WebP: {$webpPath}");
            return $storagePath;
        }

        // Hapus file asli jika ekstensi berbeda
        if ($storagePath !== $webpPath && file_exists($fullPath)) {
            @unlink($fullPath);
        }

        return $webpPath;
    }

    /**
     * Buat thumbnail WebP dari file yang sudah ada.
     *
     * @param  string   $storagePath  Path relatif di disk 'public'
     * @param  int      $width        Lebar maksimum thumbnail (default: THUMB_WIDTH)
     * @param  int      $quality      Kualitas WebP (default: QUALITY_THUMB)
     * @return string|null            Path thumbnail, atau null jika gagal
     */
    public static function createThumbnail(
        string $storagePath,
        int    $width   = self::THUMB_WIDTH,
        int    $quality = self::QUALITY_THUMB
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

        $thumbRelPath  = self::buildThumbPath($storagePath);
        $thumbFullPath = Storage::disk('public')->path($thumbRelPath);

        // Skip jika thumbnail masih lebih baru dari sumber
        if (file_exists($thumbFullPath) && filemtime($thumbFullPath) >= filemtime($fullPath)) {
            return $thumbRelPath;
        }

        self::ensureDir(dirname($thumbFullPath));

        // Jika gambar lebih kecil dari target width, salin langsung
        if ($origW <= $width) {
            $src = self::createSource($fullPath, $type);
            if (!$src) {
                return null;
            }
            $ok = imagewebp($src, $thumbFullPath, $quality);
            imagedestroy($src);
            return $ok ? $thumbRelPath : null;
        }

        [$newW, $newH] = self::calcDimensions($origW, $origH, $width, 99999);

        $src = self::createSource($fullPath, $type);
        if (!$src) {
            return null;
        }

        $thumb = self::createCanvas($newW, $newH, $type);
        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagedestroy($src);

        $ok = imagewebp($thumb, $thumbFullPath, $quality);
        imagedestroy($thumb);

        return $ok ? $thumbRelPath : null;
    }

    /**
     * Upload file, kompres, dan buat thumbnail.
     * Metode utama yang dipakai di controller.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string                          $directory  Direktori di disk 'public'
     * @return array{path: string, thumb: string}
     */
    public static function storeAndCompress($file, string $directory): array
    {
        // Simpan file asli
        $originalPath = $file->store($directory, 'public');

        // Compress → hasilkan WebP
        $compressedPath = self::compress($originalPath);

        // Buat thumbnail
        $thumbPath = self::createThumbnail($compressedPath) ?? $compressedPath;

        return [
            'path'  => $compressedPath,
            'thumb' => $thumbPath,
        ];
    }

    /**
     * Ambil path thumbnail (fallback ke original jika belum ada).
     *
     * @param  string $storagePath
     * @return string
     */
    public static function thumb(?string $storagePath): string
    {
        if (empty($storagePath)) {
            return 'defaults/no-image.webp';
        }
    
        $thumbPath = self::buildThumbPath($storagePath);
    
        return Storage::disk('public')->exists($thumbPath)
            ? $thumbPath
            : $storagePath;
    }

    /**
     * Ambil URL thumbnail (absolute URL).
     *
     * @param  string $storagePath
     * @return string
     */
    public static function thumbUrl(string $storagePath): string
    {
        return asset('storage/' . self::thumb($storagePath));
    }

    /**
     * Hapus thumbnail terkait dengan gambar.
     *
     * @param  string $storagePath
     */
    public static function deleteThumb(string $storagePath): void
    {
        $thumbPath = self::buildThumbPath($storagePath);

        if (Storage::disk('public')->exists($thumbPath)) {
            Storage::disk('public')->delete($thumbPath);
        }
    }

    /**
     * Hapus gambar utama beserta thumbnail-nya.
     *
     * @param  string $storagePath
     */
    public static function delete(string $storagePath): void
    {
        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }

        self::deleteThumb($storagePath);
    }

    /* ─── INTERNAL HELPERS ─────────────────────────────────── */

    /**
     * Bangun path thumbnail dari path gambar utama.
     * Format: thumbs/{direktori}/{nama}.webp
     *
     * Contoh:
     *   gallery/abc.webp       → thumbs/gallery/abc.webp
     *   packages/photos/x.jpg  → thumbs/packages/photos/x.webp
     */
    private static function buildThumbPath(string $storagePath): string
    {
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);

        $subDir = ($dir && $dir !== '.') ? $dir . '/' : '';

        return 'thumbs/' . $subDir . $filename . '.webp';
    }

    /**
     * Hitung dimensi baru mempertahankan aspect ratio.
     * Tidak memperbesar gambar yang sudah lebih kecil dari batas.
     *
     * @return array [width, height]
     */
    private static function calcDimensions(int $origW, int $origH, int $maxW, int $maxH): array
    {
        if ($origW <= $maxW && $origH <= $maxH) {
            return [$origW, $origH];
        }

        $ratio = min($maxW / $origW, $maxH / $origH);

        return [
            (int) round($origW * $ratio),
            (int) round($origH * $ratio),
        ];
    }

    /**
     * Buat GD image resource dari file.
     *
     * @return resource|\GdImage|false|null
     */
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

    /**
     * Buat canvas baru dengan background yang sesuai tipe file.
     * PNG & GIF: transparan | JPEG & WebP: putih
     *
     * @return resource|\GdImage
     */
    private static function createCanvas(int $w, int $h, int $type)
    {
        $canvas = imagecreatetruecolor($w, $h);

        if ($type === IMAGETYPE_PNG || $type === IMAGETYPE_GIF) {
            // Pertahankan transparansi
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $w, $h, $transparent);
        } else {
            // Background putih untuk JPEG/WebP
            $white = imagecolorallocate($canvas, 255, 255, 255);
            imagefilledrectangle($canvas, 0, 0, $w, $h, $white);
        }

        return $canvas;
    }

    /**
     * Ubah ekstensi path menjadi .webp.
     *
     * @param  string $storagePath
     * @return string
     */
    private static function toWebpPath(string $storagePath): string
    {
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);

        return ($dir && $dir !== '.')
            ? "{$dir}/{$filename}.webp"
            : "{$filename}.webp";
    }

    /**
     * Buat direktori jika belum ada.
     */
    private static function ensureDir(string $dir): void
    {
        if ($dir && !is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
}