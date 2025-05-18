<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenesEntidad extends Model
{
    use HasFactory;
    protected $table="imagenes_entidad";

    protected $fillable=[
        'entidad_id',
        'archivo',
        'type',
        'evidence_id',
    ];
    public function entidad()
    {
        return $this->belongsTo(Entidad::class, 'evidence_id', 'id');
    }

    public function evidenceImages()
    {
        return $this->belongsTo(PruebasEntidad::class, 'evidence_id', 'id');
    }
}
