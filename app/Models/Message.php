<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable =[
        "entidad_id",
        "origen_name",
        "destino_name",
        "origen_user",
        "destino_user",
        "content",
        

    ];
}
