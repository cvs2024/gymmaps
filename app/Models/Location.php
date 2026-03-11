<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    protected $fillable = [
        'name',
        'address',
        'postcode',
        'city',
        'latitude',
        'longitude',
        'website',
        'phone',
        'photo_url',
        'logo_url',
        'google_place_id',
        'opening_hours_json',
        'opening_hours_updated_at',
        'source',
        'external_id',
        'imported_at',
        'last_seen_at',
    ];

    protected $casts = [
        'opening_hours_json' => 'array',
        'opening_hours_updated_at' => 'datetime',
        'imported_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class);
    }
}
