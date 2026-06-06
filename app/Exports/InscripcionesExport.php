<?php

namespace App\Exports;

use App\Models\Inscripcion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InscripcionesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Inscripcion::with(['aspirante', 'curso', 'pago'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre Aspirante',
            'CI',
            'Correo',
            'Curso',
            'Estado Inscripción',
            'Grupo',
            'Monto Pago',
            'Estado Pago',
            'Fecha Registro',
        ];
    }

    public function map($inscripcion): array
    {
        return [
            $inscripcion->id,
            $inscripcion->aspirante->nombre_completo,
            $inscripcion->aspirante->ci,
            $inscripcion->aspirante->correo,
            $inscripcion->curso->nombre_curso,
            $inscripcion->estado,
            $inscripcion->grupo,
            $inscripcion->pago ? $inscripcion->pago->monto : '0.00',
            $inscripcion->pago ? $inscripcion->pago->estado : 'N/A',
            $inscripcion->created_at->format('d/m/Y'),
        ];
    }
}
