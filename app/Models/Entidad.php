<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    use HasFactory;

    protected $table="entidad";

    protected $fillable=[
        'nombre',
        'descripcion',
        'fecha_extravio',
        'recompensa',
        'user_id',
        'enabled',
    ];

    public function ubicaciones(){
        return $this->hasMany(Ubicaciones::class,"entidad_id");
    }

    public function files(){
        return $this->hasMany(ImagenesEntidad::class);
    }

    public function imagenes(){
        return $this->hasMany(ImagenesEntidad::class);
    }
    public function locations(){
        return $this->hasMany(Ubicaciones::class, "entidad_id");
    }


    public function contacts() {
        return $this->hasMany(PruebasEntidad::class);
    }

    public function pruebasEntidad()
{
    return $this->hasMany(PruebasEntidad::class, 'entidad_id', 'id');
}
}
