<?php

namespace App\Filament\Clusters\Visitas\Resources\Trabajadors;

use App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages\CreateTrabajador;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages\EditTrabajador;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Pages\ListTrabajadors;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Schemas\TrabajadorForm;
use App\Filament\Clusters\Visitas\Resources\Trabajadors\Tables\TrabajadorsTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Trabajador;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrabajadorResource extends Resource
{
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Trabajador::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Trabajador';
    protected static ?string $navigationLabel = 'Trabajadores'; // <-- Cambia el nombre en el menú
    protected static ?string $pluralModelLabel = 'Trabajadores'; // Corrige el título principal y las migas de pan
    protected static ?string $slug = 'trabajadores';

    public static function form(Schema $schema): Schema
    {
        return TrabajadorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrabajadorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->with(['historiales.cargo', 'historiales.area']);
    // }

    public static function getPages(): array
    {
        return [
            'index' => ListTrabajadors::route('/'),
            // 'create' => CreateTrabajador::route('/create'),
            // 'edit' => EditTrabajador::route('/{record}/edit'),
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()->hasPermissionTo('view::visitas_trabajador');
    }
}
