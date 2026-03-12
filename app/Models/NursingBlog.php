<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NursingBlog extends Model
{
    protected $table = 'nursing_blog';

    protected $fillable = [
        'text',
        'image',
        'content'
    ];
}