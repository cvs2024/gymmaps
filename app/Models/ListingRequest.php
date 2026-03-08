<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingRequest extends Model
{
    protected $fillable = [
        'contact_name',
        'business_name',
        'email',
        'phone',
        'website',
        'address',
        'postcode',
        'city',
        'sports_overview',
        'message',
        'photo_path',
    ];
}
