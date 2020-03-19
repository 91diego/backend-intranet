<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\CatalogoEstatus;

class CatalogoEstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // INICIALIZAMOS LA TABLA CON LA INFORMACION
        $data = [
            [
                'nombre' => 'Habilitado',
                'tipo' => 'estatus'
            ],
            [
                'nombre' => 'Deshabilitado',
                'tipo' => 'estatus'
            ],
            [
                'nombre' => 'Autorizado',
                'tipo' => 'respuesta_solicitud'
            ],
            [
                'nombre' => 'Rechazado',
                'tipo' => 'respuesta_solicitud'
            ],
            [
                'nombre' => 'Pendiente',
                'tipo' => 'respuesta_solicitud'
            ]
        ];

        DB::table('catalogo_estatus')->insert($data);
    }
}
