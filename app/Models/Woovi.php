<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Woovi extends Model
{
    protected $table = 'woovi';
    
    protected $fillable = [
        'app_id',
        'api_key',
        'webhook_secret',
        'url',
        'sandbox',
        'status'
    ];

    protected $casts = [
        'sandbox' => 'boolean',
        'status' => 'boolean'
    ];

    public function getApiUrl()
    {
        return $this->sandbox ? 'https://api.woovi-sandbox.com' : 'https://api.woovi.com';
    }
}
