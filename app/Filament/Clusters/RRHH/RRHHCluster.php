<?php

namespace App\Filament\Clusters\RRHH;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;

class RRHHCluster extends Cluster
{
    protected static ?string $navigationLabel = 'RR.HH';

    protected static ?string $title = 'RR.HH';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function isVisible(): bool
    {
        return auth()->user()->can('view::rrhh');
    }
}
