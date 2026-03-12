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
    ];

    protected $casts = [
        'description' => 'array', // JSON <-> Array automatic conversion
    ];
}