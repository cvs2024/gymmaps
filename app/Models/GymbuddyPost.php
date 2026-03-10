<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GymbuddyPost extends Model
{
    protected $fillable = [
        'name',
        'email',
        'profile_photo_path',
        'sport',
        'days_per_week',
        'address',
        'postcode',
        'city',
        'gender_preference',
        'about_you',
        'search_message',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
