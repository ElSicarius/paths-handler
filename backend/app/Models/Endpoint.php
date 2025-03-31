<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endpoint extends Model
{
    protected $table = 'endpoints';

    protected $fillable = [
        'path',
        'method',
        'is_active',
        'response_body',   
        'status_code',
        'status_message',
        'headers_json'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];
}
