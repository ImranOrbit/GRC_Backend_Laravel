<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineeringBlog extends Model
{
    use HasFactory;

    protected $table = 'engineering_blog';

    protected $fillable = [
        'text',
        'image',
        'content',
        'meta_title',
        'meta_description'
    ];
}