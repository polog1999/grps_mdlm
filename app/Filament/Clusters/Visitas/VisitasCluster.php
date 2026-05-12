<?php

namespace App\Filament\Clusters\Visitas;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class VisitasCluster extends Cluster
{
  
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    // protected static string|UnitEnum|null $navigationGroup = 'Gestión de Visitas';
    

}
