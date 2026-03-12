<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryRegistration extends Model
{
    use HasFactory;

    protected $table = 'country_registrations';

    protected $fillable = [
        'name',
        'email',
        'country',
        'universities'
    ];
}