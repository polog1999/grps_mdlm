<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Anuncios;
class AnunciosStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculamos los ingresos totales y los del mes actual usando relaciones de Eloquent en joins
        $ingresosTotales = Anuncios::query()
            ->join('anuncios.expedientes', 'anuncios.anuncios.expediente_id', '=', 'anuncios.expedientes.id')
            ->join('anuncios.recibo_pago', 'anuncios.expedientes.recibo_pago_id', '=', 'anuncios.recibo_pago.id')
            ->sum('anuncios.recibo_pago.monto') ?? 0;

        $ingresosMes = Anuncios::query()
            ->join('anuncios.expedientes', 'anuncios.anuncios.expediente_id', '=', 'anuncios.expedientes.id')
            ->join('anuncios.recibo_pago', 'anuncios.expedientes.recibo_pago_id', '=', 'anuncios.recibo_pago.id')
            ->whereMonth('anuncios.expedientes.fecha_expediente', now()->month)
            ->whereYear('anuncios.expedientes.fecha_expediente', now()->year)
            ->sum('anuncios.recibo_pago.monto') ?? 0;

        return [
            Stat::make('Total de Anuncios', Anuncios::count())
                ->description('Anuncios registrados históricamente')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('primary'),

            Stat::make('Ingresos Totales', 'S/ ' . number_format($ingresosTotales, 2))
                ->description('Recaudación total en soles')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Ingresos del Mes', 'S/ ' . number_format($ingresosMes, 2))
                ->description('Recaudación de ' . now()->translatedFormat('F'))
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Gráfico de línea referencial de fondo
                ->color('info'),
        ];
    }
}