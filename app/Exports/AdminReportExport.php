<?php

namespace App\Exports;

use App\Models\Aspirante;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Repositories\Admin\MetricsRepository;
use App\DTOs\Admin\DateRangeFilterData;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class AdminReportExport implements WithMultipleSheets
{
    public function __construct(private readonly array $filters = []) {}

    public function sheets(): array
    {
        return [
            new class($this->filters) implements FromCollection, WithHeadings, WithMapping, WithTitle {
                public function __construct(private readonly array $filters = []) {}
                public function title(): string
                {
                    return 'Aspirantes';
                }
                public function collection()
                {
                    return Aspirante::with('user')->latest()->get();
                }
                public function headings(): array
                {
                    return ['ID', 'Nombre', 'CI', 'Correo', 'Celular', 'Colegio', 'Año egreso', 'Registro'];
                }
                public function map($row): array
                {
                    return [$row->id, $row->nombre_completo, $row->ci, $row->correo, $row->celular, $row->colegio_procedencia, $row->anio_egreso, $row->created_at?->format('d/m/Y H:i:s')];
                }
            },
            new class($this->filters) implements FromCollection, WithHeadings, WithMapping, WithTitle {
                public function __construct(private readonly array $filters = []) {}
                public function title(): string
                {
                    return 'Inscripciones';
                }
                public function collection()
                {
                    return Inscripcion::with(['aspirante', 'curso', 'pago'])->latest()->get();
                }
                public function headings(): array
                {
                    return ['ID', 'Aspirante', 'CI', 'Curso', 'Estado', 'Grupo', 'Pago', 'Registro'];
                }
                public function map($row): array
                {
                    return [$row->id, $row->aspirante->nombre_completo, $row->aspirante->ci, $row->curso->nombre_curso, $row->estado, $row->grupo, $row->pago ? $row->pago->monto : 0, $row->created_at?->format('d/m/Y H:i:s')];
                }
            },
            new class($this->filters) implements FromCollection, WithHeadings, WithMapping, WithTitle {
                public function __construct(private readonly array $filters = []) {}
                public function title(): string
                {
                    return 'Pagos';
                }
                public function collection()
                {
                    return Pago::with(['inscripcion.aspirante', 'inscripcion.curso'])->latest()->get();
                }
                public function headings(): array
                {
                    return ['ID', 'Comprobante', 'Aspirante', 'Curso', 'Monto', 'Estado', 'Registro'];
                }
                public function map($row): array
                {
                    return [$row->id, $row->numero_comprobante, $row->inscripcion->aspirante->nombre_completo, $row->inscripcion->curso->nombre_curso, $row->monto, $row->estado, $row->created_at?->format('d/m/Y H:i:s')];
                }
            },
            new class($this->filters) implements FromCollection, WithHeadings, WithMapping, WithTitle {
                public function __construct(private readonly array $filters = []) {}
                public function title(): string
                {
                    return 'Cursos';
                }
                public function collection()
                {
                    return Curso::latest()->get();
                }
                public function headings(): array
                {
                    return ['ID', 'Curso', 'Inicio', 'Fin', 'Arancel', 'Cupos', 'Estado'];
                }
                public function map($row): array
                {
                    return [$row->id, $row->nombre_curso, optional($row->fecha_inicio)->format('d/m/Y'), optional($row->fecha_fin)->format('d/m/Y'), $row->monto_arancel, $row->cupos_disponibles, $row->is_active ? 'Activo' : 'Inactivo'];
                }
            },
            new class($this->filters) implements FromArray, WithHeadings, WithTitle {
                public function __construct(private readonly array $filters = []) {}
                public function title(): string
                {
                    return 'Estadisticas';
                }
                public function array(): array
                {
                    $metrics = app(MetricsRepository::class);
                    $range = DateRangeFilterData::fromArray($this->filters);
                    return [
                        ['Aspirantes por mes', json_encode($metrics->monthlyAspirantes($range), JSON_UNESCAPED_UNICODE)],
                        ['Inscripciones por mes', json_encode($metrics->monthlyInscripciones($range), JSON_UNESCAPED_UNICODE)],
                        ['Ingresos por mes', json_encode($metrics->monthlyIngresos($range), JSON_UNESCAPED_UNICODE)],
                        ['Pagos por estado', json_encode($metrics->pagosPorEstado($range), JSON_UNESCAPED_UNICODE)],
                    ];
                }
                public function headings(): array
                {
                    return ['Métrica', 'Valor'];
                }
            },
        ];
    }
}
