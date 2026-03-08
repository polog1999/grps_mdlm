<?php

namespace App\Filament\Clusters\RRHH\Resources\InformeActividads;

use App\Filament\Clusters\RRHH\Resources\InformeActividads\Pages\ListInformeActividads;
use App\Filament\Clusters\RRHH\Resources\InformeActividads\Schemas\InformeActividadForm;
use App\Filament\Clusters\RRHH\Resources\InformeActividads\Tables\InformeActividadsTable;
use App\Filament\Clusters\RRHH\RRHHCluster;
use App\Models\InformeActividad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
class InformeActividadResource extends Resource
{
    protected static ?string $model = InformeActividad::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $cluster = RRHHCluster::class;

    protected static ?string $navigationLabel = 'Informes de Actividades';

    protected static ?string $modelLabel = 'Informe de Actividad';

    protected static ?string $pluralModelLabel = 'Informes de Actividades';
    protected static string|UnitEnum|null $navigationGroup = 'Teletrabajo';


    protected static ?string $recordTitleAttribute = 'InformeActividad';

    public static function form(Schema $schema): Schema
    {
        return InformeActividadForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InformeActividadsTable::configure($table);
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
            'index' => ListInformeActividads::route('/'),
        ];
    }
}
