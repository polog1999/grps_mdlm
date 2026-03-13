<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Widgets;

use App\Filament\Clusters\Sil\Resources\Anuncios\Pages\ListAnuncios;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Anuncios;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Support\Carbon;

class AnunciosStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListAnuncios::class;
    }
    protected function getStats(): array
    {
        $queryBase = $this->getPageTableQuery();

        $ingresosFiltrados = (clone $queryBase)
            ->join('anuncios.expedientes as e_filtrado', 'anuncios.anuncios.expediente_id', '=', 'e_filtrado.id')
            ->join('anuncios.recibo_pago as r_filtrado', 'e_filtrado.recibo_pago_id', '=', 'r_filtrado.id')
            ->sum('r_filtrado.monto') ?? 0;

        $totalFiltrados = (clone $queryBase)->count();

        $ingresosMesActual = Anuncios::query()
            ->join('anuncios.expedientes', 'anuncios.anuncios.expediente_id', '=', 'anuncios.expedientes.id')
            ->join('anuncios.recibo_pago', 'anuncios.expedientes.recibo_pago_id', '=', 'anuncios.recibo_pago.id')
            ->whereMonth('anuncios.expedientes.fecha_expediente', now()->month)
            ->whereYear('anuncios.expedientes.fecha_expediente', now()->year)
            ->sum('anuncios.recibo_pago.monto') ?? 0;

        // Detectar si hay filtros de fecha activos para el título
        $filters = $this->tableFilters;
        $fechaDesde = $filters['fecha_expediente']['desde'] ?? null;
        $fechaHasta = $filters['fecha_expediente']['hasta'] ?? null;

        $tituloIngresos = 'Ingresos Totales';

        if ($fechaDesde || $fechaHasta) {
            $tituloIngresos = 'Ingresos Filtrados';
            $rango = [];
            if ($fechaDesde)
                $rango[] = 'desde ' . Carbon::parse($fechaDesde)->format('d/m/Y');
            if ($fechaHasta)
                $rango[] = 'hasta ' . Carbon::parse($fechaHasta)->format('d/m/Y');
            $tituloIngresos .= ' (' . implode(' ', $rango) . ')';
        }

        return [
            Stat::make('Anuncios Seleccionados', $totalFiltrados)
                ->description('Según los filtros aplicados')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make($tituloIngresos, 'S/ ' . number_format($ingresosFiltrados, 2))
                ->description($fechaDesde || $fechaHasta ? 'Recaudación en el rango seleccionado' : 'Recaudación total histórica')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Ingresos del Mes (' . now()->translatedFormat('F') . ')', 'S/ ' . number_format($ingresosMesActual, 2))
                ->description('Total del mes actual')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
        ];
    }
}