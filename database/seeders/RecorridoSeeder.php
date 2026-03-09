<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecorridoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recorridos = [
            [
                'nombre' => 'Recorrido General',
                'tipo' => 'No Guiado',
                'precio' => 15.00,
                'duracion' => 60,
                'capacidad' => 50,
            ],
            [
                'nombre' => 'Felinos VIP',
                'tipo' => 'Guiado',
                'precio' => 50.00,
                'duracion' => 90,
                'capacidad' => 50,
            ],
            [
                'nombre' => 'Osos Andinos',
                'tipo' => 'Guiado',
                'precio' => 45.00,
                'duracion' => 80,
                'capacidad' => 50,
            ],
            [
                'nombre' => 'Cóndores del Cielo',
                'tipo' => 'Guiado',
                'precio' => 40.00,
                'duracion' => 70,
                'capacidad' => 50,
            ],
            [
                'nombre' => 'Acuario Amazónico',
                'tipo' => 'No Guiado',
                'precio' => 20.00,
                'duracion' => 50,
                'capacidad' => 50,
            ],
            [
                'nombre' => 'Recorrido Interactivo',
                'tipo' => 'Guiado',
                'precio' => 30.00,
                'duracion' => 75,
                'capacidad' => 50,
            ],
        ];

        DB::table('recorridos')->insert($recorridos);
    }
}
