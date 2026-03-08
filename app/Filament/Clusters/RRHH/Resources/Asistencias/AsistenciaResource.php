<?php

namespace App\Filament\Clusters\RRHH\Resources\Asistencias;

use App\Filament\Clusters\RRHH\Resources\Asistencias\Pages\CreateAsistencia;
use App\Filament\Clusters\RRHH\Resources\Asistencias\Pages\EditAsistencia;
use App\Filament\Clusters\RRHH\Resources\Asistencias\Pages\ListAsistencias;
use App\Filament\Clusters\RRHH\Resources\Asistencias\Schemas\AsistenciaForm;
use App\Filament\Clusters\RRHH\Resources\Asistencias\Tables\AsistenciasTable;
use App\Filament\Clusters\RRHH\RRHHCluster;
use App\Models\Asistencia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
class AsistenciaResource extends Resource
{
    protected static ?string $model = Asistencia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $cluster = RRHHCluster::class;

    protected static ?string $navigationLabel = 'Asistencias';

    protected static ?string $modelLabel = 'Asistencia';
    protected static string|UnitEnum|null $navigationGroup = 'Teletrabajo';

    protected static ?string $pluralModelLabel = 'Asistencias';

    protected static ?string $recordTitleAttribute = 'Asistencia';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('view::asistencias');
    }

    public static function form(Schema $schema): Schema
    {
        return AsistenciaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AsistenciasTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('usuario_id', auth()->id());
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
            'index' => ListAsistencias::route('/'),
        ];
    }
}
