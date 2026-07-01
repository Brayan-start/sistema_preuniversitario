<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Curso::create([
            'nombre_curso' => 'Preuniversitario Gestión II/2026',
            'descripcion' => 'Curso de nivelación para ingreso a la Carrera de Ingeniería de Sistemas.',
            'fecha_inicio' => '2026-08-01',
            'fecha_fin' => '2026-12-15',
            'monto_arancel' => 500.00,
            'cupos_disponibles' => 200,
            'requisitos' => '1. Fotocopia de CI. 2. Certificado de Bachillerato. 3. Fotografía 4x4 fondo azul.',
            'is_active' => true,
        ]);
    }
}
