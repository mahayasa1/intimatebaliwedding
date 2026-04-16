<?php

namespace App\Models;

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
        'photo' => 'array',
    ];

    public function getPhotoAttribute($value)
    {
        if (is_null($value)) return [];
        if (is_string($value)) return json_decode($value, true) ?? [];
        return $value;
    }

    public function setPhotoAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['photo'] = json_encode($value);
        } elseif (is_null($value)) {
            $this->attributes['photo'] = json_encode([]);
        } else {
            $this->attributes['photo'] = $value;
        }
    }

    /**
     * Ekstrak YouTube Video ID dari berbagai format URL.
     * Mendukung: youtube.com/watch?v=, youtu.be/, youtube.com/embed/
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (!$this->video_url) return null;

        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $this->video_url,
            $matches
        );

        return $matches[1] ?? null;
    }

    /**
     * Embed URL untuk iframe YouTube.
     */
    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $id = $this->youtube_id;
        if (!$id) return null;

        return "https://www.youtube.com/embed/{$id}?rel=0&modestbranding=1";
    }

    /**
     * Thumbnail YouTube resolusi tinggi.
     */
    public function getYoutubeThumbnailAttribute(): ?string
    {
        $id = $this->youtube_id;
        if (!$id) return null;

        return "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
    }

    /**
     * Apakah ini gallery video?
     */
    public function isVideo(): bool
    {
        return $this->type === 'video' || !empty($this->video_url);
    }
}