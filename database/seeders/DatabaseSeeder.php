<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Ejecutar el seeder de Roles y Permisos primero
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Crear usuario administrador de prueba
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Asignar el rol de Administrador
        if (!$user->hasRole('Administrador')) {
            $user->assignRole('Administrador');
        }
    }
}