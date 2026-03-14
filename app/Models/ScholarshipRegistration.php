<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipRegistration extends Model
{
    protected $table = 'scholarship_registrations';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'scholarship_country',
        'meta_title',
        'meta_description'
    ];
}