<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodHospitalityBlog extends Model
{
    use HasFactory;

    protected $table = 'food_hospitality_blog';

    protected $fillable = [
        'text',
        'image',
        'content',
        'meta_title',
        'meta_description'
    ];
}