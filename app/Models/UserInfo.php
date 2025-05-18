<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $table = "info_user";

    protected $fillable = [
        'user',
        'nombre',
        'ap',
        'am',
        'direccion',
        'ciudad',
        'estado',
        'cp',
        'telefono',
        'public_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }


    protected $hidden = [
      
    ];

    protected $casts = [
    
    ];
}
