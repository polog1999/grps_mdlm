<?php

namespace App\Filament\Clusters\Sil\Resources\LicenciaRelacions;

use App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Pages\CreateLicenciaRelacion;
use App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Pages\EditLicenciaRelacion;
use App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Pages\ListLicenciaRelacions;
use App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Schemas\LicenciaRelacionForm;
use App\Filament\Clusters\Sil\Resources\LicenciaRelacions\Tables\LicenciaRelacionsTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\LicenciaRelacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class LicenciaRelacionResource extends Resource
{
    protected static ?string $model = LicenciaRelacion::class;
    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'Licencias Relacionadas';
    protected static ?string $navigationLabel = 'Licencias Relacionadas';
    protected static ?string $pluralModelLabel = 'Licencias Relacionadas';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-m-arrow-path-rounded-square';

    protected static ?string $cluster = SilCluster::class;

    protected static string|UnitEnum|null $navigationGroup = 'Licencias de Funcionamiento';


    public static function form(Schema $schema): Schema
    {
        return LicenciaRelacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LicenciaRelacionsTable::configure($table);
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->can('view::licencia_relacion') ?? false;
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
            'index' => ListLicenciaRelacions::route('/'),
            //'create' => CreateLicenciaRelacion::route('/create'),
            //'edit' => EditLicenciaRelacion::route('/{record}/edit'),
        ];
    }
}
