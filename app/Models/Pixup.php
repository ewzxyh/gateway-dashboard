<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pixup extends Model
{
    protected $table = "pixup";
    
    protected $fillable = [
        "client_id",
        "client_secret",
        "url",
        "webhook_secret",
    ];
}