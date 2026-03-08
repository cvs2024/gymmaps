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
        'source',
        'external_id',
        'imported_at',
        'last_seen_at',
    ];

    protected $casts = [
        'imported_at' => 'datetime',
        'last_seen_at' => 'datetime',
    ];

    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class);
    }
}
