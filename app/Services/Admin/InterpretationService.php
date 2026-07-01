<?php

namespace App\Services\Admin;

class InterpretationService
{
    public function build(array $filters, array $metrics): array
    {
        $inscripciones = $metrics['total_inscripciones'] ?? 0;
        $pagos = $metrics['total_pagos_realizados'] ?? 0;
        $porcentaje = $metrics['porcentaje_pagos_completados'] ?? 0;

        return [
            'resumen' => 'Los indicadores consolidados se calculan a partir de los filtros actuales y la información vigente en la base de datos.',
            'inscripciones' => sprintf('Se registran %d inscripciones en el período analizado.', $inscripciones),
            'pagos' => sprintf('Existen %d pagos aprobados con una cobertura del %.2f%%.', $pagos, $porcentaje),
        ];
    }
}
