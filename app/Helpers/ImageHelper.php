<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Buat thumbnail WebP dari file yang sudah tersimpan di storage.
     *
     * @param  string  $storagePath  Path relatif di disk 'public' (e.g. "packages/foto.jpg")
     * @param  int     $width        Lebar thumbnail dalam pixel (default 800)
     * @param  int     $quality      Kualitas WebP 0–100 (default 82)
     * @return string|null           Path thumbnail (relatif) atau null jika gagal
     */
    public static function createThumbnail(string $storagePath, int $width = 800, int $quality = 82): ?string
    {
        $fullPath = Storage::disk('public')->path($storagePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        $imageInfo = getimagesize($fullPath);
        if (!$imageInfo) {
            return null;
        }

        [$origW, $origH, $type] = $imageInfo;

        // Kalau gambar sudah lebih kecil dari lebar target, tidak perlu resize
        if ($origW <= $width) {
            return $storagePath;
        }

        $height = (int) ($origH * ($width / $origW));

        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($fullPath),
            IMAGETYPE_PNG  => imagecreatefrompng($fullPath),
            IMAGETYPE_WEBP => imagecreatefromwebp($fullPath),
            default        => null,
        };

        if (!$source) {
            return null;
        }

        $thumb = imagecreatetruecolor($width, $height);

        // Preserve transparency untuk PNG
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefilledrectangle($thumb, 0, 0, $width, $height, $transparent);
        }

        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $origW, $origH);

        // Susun path thumbnail: thumbs/<direktori_asli>/<nama_file>.webp
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);
        $thumbRelPath = 'thumbs/' . ($dir !== '.' ? $dir . '/' : '') . $filename . '.webp';

        $thumbFullPath = Storage::disk('public')->path($thumbRelPath);

        // Buat direktori jika belum ada
        $thumbDir = dirname($thumbFullPath);
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0755, true);
        }

        imagewebp($thumb, $thumbFullPath, $quality);
        imagedestroy($thumb);
        imagedestroy($source);

        return $thumbRelPath;
    }

    /**
     * Ambil path thumbnail. Jika thumbnail tidak ada, kembalikan path original.
     *
     * @param  string  $storagePath  Path relatif di disk 'public'
     * @return string
     */
    public static function thumb(string $storagePath): string
    {
        $dir      = pathinfo($storagePath, PATHINFO_DIRNAME);
        $filename = pathinfo($storagePath, PATHINFO_FILENAME);
        $thumbPath = 'thumbs/' . ($dir !== '.' ? $dir . '/' : '') . $filename . '.webp';

        return Storage::disk('public')->exists($thumbPath) ? $thumbPath : $storagePath;
    }

    /**
     * Hapus thumbnail yang terkait dengan sebuah file.
     *
     * @param  string  $storagePath  Path relatif di disk 'public'
     * @return void
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
}