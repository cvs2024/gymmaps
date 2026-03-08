<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawImport extends Model
{
    protected $fillable = [
        'source',
        'external_id',
        'import_hash',
        'payload',
        'status',
        'error_message',
        'processed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];
}
