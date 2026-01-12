<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos;

use App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Pages\CreateLicenciasLevantamiento;
use App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Pages\EditLicenciasLevantamiento;
use App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Pages\ListLicenciasLevantamientos;
use App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Schemas\LicenciasLevantamientoForm;
use App\Filament\Clusters\Sil\Resources\LicenciasLevantamientos\Tables\LicenciasLevantamientosTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\CertificadoLicenciaFuncionamiento;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LicenciasLevantamientoResource extends Resource
{
    protected static ?string $model = CertificadoLicenciaFuncionamiento::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-map-pin-area-bold';

    protected static ?string $cluster = SilCluster::class;
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'CertificadoLicenciaFuncionamiento';

    protected static ?string $navigationLabel = 'Data de Levantamiento';
    protected static ?string $pluralModelLabel = 'Data de Levantamiento';

    public static function form(Schema $schema): Schema
    {
        return LicenciasLevantamientoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LicenciasLevantamientosTable::configure($table);
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
            'index' => ListLicenciasLevantamientos::route('/'),
            //'create' => CreateLicenciasLevantamiento::route('/create'),
            //'edit' => EditLicenciasLevantamiento::route('/{record}/edit'),
        ];
    }
}
