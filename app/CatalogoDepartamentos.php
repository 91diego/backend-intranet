<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoDepartamentos extends Model
{
    protected $fillable = [
        'id_depto_crm',
        'nombre',
        'id_depto_padre_crm',
        'id_depto_responsable_crm'
    ];
}
