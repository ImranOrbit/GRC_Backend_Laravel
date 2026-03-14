<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';

    protected $fillable = [
        'url',
        'title',
        'thumbnail',
        'tags',
        'meta_title',
        'meta_description'
    ];

    protected $casts = [
        'tags' => 'array'
    ];
}