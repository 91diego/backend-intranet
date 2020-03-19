<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // INICIALIZAMOS LA TABLA CON EL USUARIO ADMIN
        User::create([
            'name' => 'Administrador',
            'email' => 'dgonzalez@idex.cc',
            'password' => bcrypt('admin123')
        ]);
    }
}
