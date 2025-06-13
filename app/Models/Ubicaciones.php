<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicaciones extends Model
{
    use HasFactory;

    protected $table="ubicaciones";

    protected $fillable=[
        'entidad_id',
        'latitud',
        'longitud',
        'principal',
    ];

    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }
}
