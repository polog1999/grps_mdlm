<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\CertificadoLicenciaFuncionamiento;
use App\Services\Sil\Widgets\NivelesRiesgoLicenciasService;
class NivelesRiesgoChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()->can('view::niveles_riesgo_chart');
    }

    protected ?string $heading = 'Licencia por Nivel de Riesgo';

    protected static ?int $sort = 2;
    protected ?string $maxHeight = '250px';


    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hoy',
            'week' => 'Esta semana',
            'month' => 'Este mes',
            'year' => 'Este año',
            'all' => 'Todo el tiempo',
        ];
    }




    protected function getData(): array
    {
        /*

        obtenerDatosParaChart()
         "RIESGO MUY ALTO" => 7
         "RIESGO MEDIO" => 352
         "RIESGO ALTO" => 23
         "RIESGO BAJO" => 5*/

        $data = (new NivelesRiesgoLicenciasService())->obtenerDatosParaChart($this->filter);
        $colors = array_map(function ($label) {
            return NivelesRiesgoLicenciasService::getColorByDescripcion($label);
        }, array_keys($data));
        return [
            'datasets' => [
                [
                    'label' => 'Licencias',
                    'data' => array_values($data),
                    'backgroundColor' => $colors,
                    'borderColor' => '#ffffffff',
                    'borderRadius' => 10,

                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
                'duration' => 1500,
                'easing' => 'easeInOutQuart',
            ],
            'responsive' => true,
        ];
    }
}
