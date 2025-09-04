<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'artic_id',
        'title',
        'description',
        'image_id',
        'file_path',
        'favorite',
    ];

    protected $casts = [
        'favorite' => 'boolean',
    ];
}
