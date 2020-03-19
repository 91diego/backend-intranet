<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\CatalogoMotivoPermisos;

class CatalogoMotivoPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('catalogo_motivo_permisos')->truncate();
        // INICIALIZAMOS LA TABLA CON LA INFORMACION
        $data = [
            [
                'nombre' => 'Vacaciones',
                'id_estatus' => 1
            ],
            [
                'nombre' => 'Permiso con goce de sueldo',
                'tipo' => 1
            ],
            [
                'nombre' => 'Permiso sin goce de sueldo',
                'tipo' => 1
            ],
            [
                'nombre' => 'Enfermedad',
                'tipo' => 1
            ],
            [
                'nombre' => 'Otro',
                'tipo' => 1
            ]
        ];
        
        DB::table('catalogo_motivo_permisos')->insert($data);
    }
}
