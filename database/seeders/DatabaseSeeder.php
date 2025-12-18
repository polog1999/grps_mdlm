<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear o recuperar el usuario
        // Guardamos el resultado en la variable $user
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'), // Es mejor encriptarla aquí
                'email_verified_at' => now(),
            ]
        );

        // 2. Asegurarse de que el Rol existe en la BD
        // Esto evita errores si el rol aún no ha sido creado por otro seeder
        $role = Role::firstOrCreate(['name' => 'Administrador']);

        // 3. Asignar el rol de Administrador
        if (!$user->hasRole('Administrador')) {
            $user->assignRole('Administrador');
            $this->command->info('Rol Administrador asignado al usuario test@example.com');
        }
    }
}