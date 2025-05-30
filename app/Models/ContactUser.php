<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUser extends Model
{
    use HasFactory;
    protected $table = "contacto_user";
    protected $fillable=[
        'usuario_contacto',
        'usuario_final',
        'evidence_id',
    ];

}
