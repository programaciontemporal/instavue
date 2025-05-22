<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Support\Facades\Hash; // Para hashear la contraseña

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Antonio García',
            'email' => 'antonio@correo.com',
            'password' => Hash::make('123456789'), // Hashea la contraseña
            'email_verified_at' => now(), // Simula que el email está verificado
            'avatar' => null, // Inicialmente null, o podrías poner una URL por defecto aquí
        ]);

        // Puedes añadir más usuarios aquí si lo necesitas
        User::create([
            'name' => 'María López',
            'email' => 'maria@correo.com',
            'password' => Hash::make('123456789'),
            'email_verified_at' => now(),
            'avatar' => null,
        ]);
    }
}
