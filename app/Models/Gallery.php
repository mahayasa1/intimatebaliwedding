<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'photo',
        'video_url',
        'type',
        'description',
        'category',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
        // 'photo' TIDAK didaftarkan di sini lagi.
        // Jangan gabungkan $casts dan getXAttribute/setXAttribute
        // untuk kolom yang sama — accessor lama akan MENIMPA cast
        // (Eloquent skip cast kalau accessor terdeteksi), sehingga
        // nilai yang terbaca bisa tidak konsisten (kadang string
        // JSON mentah, kadang array) tergantung titik lifecycle model.
        // Ini yang menyebabkan $gallery->photo kadang gagal ke-decode
        // dan card gallery render tanpa gambar (placeholder kosong).
    ];

    /**
     * Accessor + Mutator modern (Attribute::make) — satu-satunya
     * sumber kebenaran untuk kolom 'photo'. Menggantikan
     * getPhotoAttribute()/setPhotoAttribute() lama sekaligus
     * menggantikan $casts['photo'] => 'array'.
     */
    protected function photo(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_null($value) || $value === '') {
                    return [];
                }
                if (is_array($value)) {
                    return $value;
                }
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            },
            set: function ($value) {
                if (is_null($value)) {
                    return json_encode([]);
                }
                if (is_array($value)) {
                    return json_encode(array_values($value));
                }
                // Sudah berupa string (misal sudah JSON) — simpan apa adanya
                return $value;
            },
        );
    }

    /**
     * Ekstrak YouTube Video ID dari berbagai format URL.
     * Mendukung: youtube.com/watch?v=, youtu.be/, youtube.com/embed/
     */
    protected function youtubeId(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->video_url) {
                    return null;
                }

                preg_match(
                    '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
                    $this->video_url,
                    $matches
                );

                return $matches[1] ?? null;
            },
        );
    }

    /**
     * Embed URL untuk iframe YouTube.
     */
    protected function youtubeEmbedUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                $id = $this->youtube_id;
                return $id ? "https://www.youtube.com/embed/{$id}?rel=0&modestbranding=1" : null;
            },
        );
    }

    /**
     * Thumbnail utama: video → YouTube thumbnail, foto → storage.
     */
    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->isVideo()) {
                    return $this->youtube_thumbnail;
                }
                return $this->image ? asset('storage/' . $this->image) : null;
            },
        );
    }

    /**
     * Thumbnail YouTube resolusi tinggi.
     */
    protected function youtubeThumbnail(): Attribute
    {
        return Attribute::make(
            get: function () {
                $id = $this->youtube_id;
                return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
            },
        );
    }

    /**
     * Apakah ini gallery video?
     */
    public function isVideo(): bool
    {
        return $this->type === 'video' || !empty($this->video_url);
    }
}