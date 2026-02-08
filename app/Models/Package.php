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
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}