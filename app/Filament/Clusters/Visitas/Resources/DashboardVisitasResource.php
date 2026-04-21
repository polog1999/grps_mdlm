<?php

// namespace App\Filament\Clusters\Visitas\Resources;

// use App\Filament\Clusters\Visitas\VisitasCluster;
// use App\Filament\Clusters\Visitas\Pages\DashboardVisitas;
// use BackedEnum;
// use Filament\Resources\Resource;
// use UnitEnum;

// // DashboardVisitasResource.php

// class DashboardVisitasResource extends Resource
// {
//     protected static ?string $cluster = VisitasCluster::class;
//     protected static bool $shouldRegisterNavigation = true; // CÁMBIALO A TRUE

//     // Esto es lo que saca las cosas del escritorio y las mete en una carpeta
//     protected static string | UnitEnum | null $navigationGroup = 'Panel de Análisis'; 
//     protected static ?string $navigationLabel = 'Tablero de Control';

//     public static function getPages(): array
//     {
//         return [
//             'index' => DashboardVisitas::route('/'),
//         ];
//     }
// }