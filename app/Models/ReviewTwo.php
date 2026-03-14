<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewTwo extends Model
{
    protected $table = 'reviewtwo';

    protected $fillable = [
        'name',
        'review_text',
        'image_url',
        'meta_title',
        'meta_description'
    ];
}