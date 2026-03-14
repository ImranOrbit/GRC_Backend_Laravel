<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'name',
        'review_text',
        'rating',
        'image_url',
        'meta_title',
        'meta_description'
    ];
}
