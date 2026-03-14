<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leadership extends Model
{
    use HasFactory;

    protected $table = 'leadership';

    protected $fillable = [
        'name',
        'title',
        'image',
        'description',
        'meta_title',
        'meta_description'
    ];

    // Remove JSON casting since we're storing as text now
    protected $casts = [
        // 'description' => 'array', // Remove this if you want to store as text
    ];
}