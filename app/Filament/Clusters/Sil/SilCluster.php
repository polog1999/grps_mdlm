<?php

namespace App\Filament\Clusters\Sil;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class SilCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'tabler-license';
    protected static ?string $navigationLabel = 'SIL & ITSE';
}
