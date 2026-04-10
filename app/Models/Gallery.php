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
}