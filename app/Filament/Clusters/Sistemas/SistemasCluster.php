<?php

namespace App\Filament\Clusters\Sistemas;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SistemasCluster extends Cluster
{

    protected static string|BackedEnum|null $navigationIcon = 'grommet-system';
    protected static ?string $navigationLabel = 'Sistemas';
}