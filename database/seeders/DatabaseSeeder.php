<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuario administrador
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
        ]);
        
        // Ejecutar seeders en orden de dependencias
        $this->call([
            EmpleadoSeeder::class,
            ContratoSeeder::class,
            AltaBajaSeeder::class,
            EmpleadoRenglonSeeder::class,
            ContratoRenglonSeeder::class,
            AlertaVencimientoSeeder::class,
            ReporteSeeder::class,
        ]);
    }
}
