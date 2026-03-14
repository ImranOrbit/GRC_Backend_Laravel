<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaSuccess extends Model
{
    protected $table = 'visa_success';

    protected $fillable = [
        'image',
        'text',
        'meta_title',
        'meta_description'
    ];
}