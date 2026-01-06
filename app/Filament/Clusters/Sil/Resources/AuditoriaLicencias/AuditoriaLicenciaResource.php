<?php

namespace App\Filament\Clusters\Sil\Resources\AuditoriaLicencias;

use App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Pages\CreateAuditoriaLicencia;
use App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Pages\EditAuditoriaLicencia;
use App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Pages\ListAuditoriaLicencias;
use App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Schemas\AuditoriaLicenciaForm;
use App\Filament\Clusters\Sil\Resources\AuditoriaLicencias\Tables\AuditoriaLicenciasTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\AuditoriaLicencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AuditoriaLicenciaResource extends Resource
{
    protected static ?string $model = AuditoriaLicencia::class;
    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = SilCluster::class;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view::auditoria_licencias') ?? false;
    }
    protected static ?string $recordTitleAttribute = 'AuditoriaLicencia';

    public static function form(Schema $schema): Schema
    {
        return AuditoriaLicenciaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AuditoriaLicenciasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAuditoriaLicencias::route('/'),
            //'create' => CreateAuditoriaLicencia::route('/create'),
            //'edit' => EditAuditoriaLicencia::route('/{record}/edit'),
        ];
    }
}
