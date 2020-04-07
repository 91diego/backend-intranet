<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApartadosCrm extends Model
{
    protected $fillable = [
        'id_negociacion',
        'id_lead',
        'nombre_negociacion',
        'producto1',
        'producto2',
        'total',
        'precio_producto',
        'estatus_apartado',
        'id_responsable',
        'desarrollo'
    ];
}
