<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'registrations';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'nearest_office',
        'preferred_destination',
        'test_status',
        'funding_plan'
    ];
}