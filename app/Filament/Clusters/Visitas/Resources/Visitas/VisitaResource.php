<?php

namespace App\Filament\Clusters\Visitas\Resources\Visitas;

use App\Filament\Clusters\Visitas\Resources\Visitas\Pages\CreateVisita;
use App\Filament\Clusters\Visitas\Resources\Visitas\Pages\EditVisita;
use App\Filament\Clusters\Visitas\Resources\Visitas\Pages\ListVisitas;
use App\Filament\Clusters\Visitas\Resources\Visitas\Schemas\VisitaForm;
use App\Filament\Clusters\Visitas\Resources\Visitas\Tables\VisitasTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Visita;
use App\Models\VisitaHistorico;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VisitaResource extends Resource
{
    protected static ?string $model = VisitaHistorico::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Visitas'; // <-- Cambia el nombre en el menú
    protected static ?string $pluralModelLabel = 'Visitas'; // Corrige el título principal y las migas de pan
    protected static ?string $modelLabel = 'Visita'; // Nombre en singular para botones de "Crear
    protected static ?string $breadcrumb = 'Visitas';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Visita';



    public static function form(Schema $schema): Schema
    {
        return VisitaForm::configure($schema)->model(Visita::class);
    }

    public static function table(Table $table): Table
    {
        return VisitasTable::configure($table);
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
            'index' => ListVisitas::route('/'),
            'create' => CreateVisita::route('/create'),
            // 'edit' => EditVisita::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::visitas_visita');
    }
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery()->with('sede');

    // Si el usuario NO es admin, filtramos por su ID
    if (!auth()->user()->hasRole('Administrador OTIE')) { 
        $query->where('user_id_ingreso', auth()->id());
    }

    return $query;
}
}
