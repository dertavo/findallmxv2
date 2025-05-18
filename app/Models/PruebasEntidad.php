<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PruebasEntidad extends Model
{
    use HasFactory;

    protected $table ="pruebas_entidad";

    protected $fillable=[
        'descripcion',
        'archivo',
        'contact_user',
        'entidad_id',
        "status",
        "handshake",
        "destino_user",
    ];
protected $appends = ['nombre_entidad','username_destino_user','id_destino_user'];

    public function contactUser() {
        return $this->hasOne(User::class,'id','contact_user');
    }

    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'entidad_id', 'id');
    }

    public function destinoUser(){
        return $this->belongsTo(User::class, 'destino_user','id');
    }

    public function imagenesEntidad()
    {
        return $this->hasMany(ImagenesEntidad::class, 'evidence_id', 'id');
    }

    public function getNombreEntidadAttribute()
    {
        return $this->entidad ? $this->entidad->nombre : null;
    }
    public function getUsernameDestinoUserAttribute()
    {
        return $this->destinoUser ? $this->destinoUser->username : null;
    }

    public function getIdDestinoUserAttribute()
{
    return $this->destinoUser ? $this->destinoUser->id : null;
}

}
