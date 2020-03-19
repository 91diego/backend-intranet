<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\CatalogoMotivoPermisos;
use App\CatalogoEstatus;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // $this->call(UsersTableSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CatalogoMotivoPermisosSeeder::class);
        $this->call(CatalogoEstatusSeeder::class);
        Model::reguard();
    }
}
