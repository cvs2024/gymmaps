<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalTrainerRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'training_location',
        'city',
        'days_per_week',
        'sport_focus',
        'max_rate',
        'goal',
        'message',
        'is_active',
    ];

    protected $casts = [
        'max_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
