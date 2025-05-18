<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotiUser extends Model
{
    use HasFactory;

    protected $table ='notificaciones_user';

    protected $fillable = [
        "descripcion",
        "tipo",
        "origen_user",
        "destino_user",
        "entidad",
      

    ];
}
