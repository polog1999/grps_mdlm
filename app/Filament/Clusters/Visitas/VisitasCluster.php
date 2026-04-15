<?php

namespace App\Filament\Clusters\Visitas;

use App\Filament\Clusters\Visitas\Resources\Visitas\VisitaResource;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class VisitasCluster extends Cluster
{
  
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;
  
}
