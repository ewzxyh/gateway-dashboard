<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BSPay extends Model
{
    protected $table = "bspay";
    
    protected $fillable = [
        "client_id",
        "client_secret",
        "url",
        "webhook_secret",
    ];
}
