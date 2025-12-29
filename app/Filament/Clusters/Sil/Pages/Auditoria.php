<?php

namespace App\Filament\Clusters\Sil\Pages;

use App\Filament\Clusters\Sil\SilCluster;
use Filament\Pages\Page;
use BackedEnum;

class Auditoria extends Page
{
    protected string $view = 'filament.clusters.sil.pages.auditoria';

    protected static ?string $cluster = SilCluster::class;


    protected static ?string $recordTitleAttribute = 'Auditoria';
    protected static ?string $navigationLabel = 'Auditoria';
    protected static ?string $pluralModelLabel = 'Auditoria';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

}
