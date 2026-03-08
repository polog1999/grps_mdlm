<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigrarPersonalAUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtenemos los datos de tu tabla temporal
        $personal = DB::table('itse.tmp_personal_carga')->get();

        foreach ($personal as $persona) {

            DB::table('itse.users')->updateOrInsert(
                ['email' => $persona->username], // Usamos el username como identificador único en 'email'
                [
                    'name' => $persona->nombre_completo,
                    // Hasheamos el DNI directamente con el algoritmo por defecto de Laravel (Bcrypt/Argon2)
                    'password' => Hash::make(trim($persona->dni)),
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Se han procesado ' . $personal->count() . ' usuarios correctamente.');
    }
}