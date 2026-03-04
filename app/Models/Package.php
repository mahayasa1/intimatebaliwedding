<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'image',
        'photo',
    ];

    protected $casts = [
        'photo' => 'array',
    ];

    // Accessor to ensure photo is always an array
    public function getPhotoAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        
        return $value;
    }

    // Mutator to ensure photo is saved as JSON
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