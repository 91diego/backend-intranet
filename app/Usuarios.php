<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuarios extends Model
{
    protected $fillable = [
        'id_usuario_crm',
        'nombre',
        'apellido',
        'email',
        'empresa',
        'puesto',
        'estatus',
        'id_usuario_crm',
        'id_vacaciones',
        'id_depto_crm'
    ];
}
