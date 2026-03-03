<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios;

use App\Filament\Clusters\Sil\Resources\Anuncios\Pages\CreateAnuncios;
use App\Filament\Clusters\Sil\Resources\Anuncios\Pages\EditAnuncios;
use App\Filament\Clusters\Sil\Resources\Anuncios\Pages\ListAnuncios;
use App\Filament\Clusters\Sil\Resources\Anuncios\Schemas\AnunciosForm;
use App\Filament\Clusters\Sil\Resources\Anuncios\Tables\AnunciosTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\Anuncios;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum; // Asegúrate de importar esto arriba si no está

class AnunciosResource extends Resource
{
    protected static ?string $model = Anuncios::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = SilCluster::class;

    protected static string|UnitEnum|null $navigationGroup = 'Anuncios';
    protected static ?string $recordTitleAttribute = 'Anuncios';

    public static function form(Schema $schema): Schema
    {
        return AnunciosForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnunciosTable::configure($table);
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
            'index' => ListAnuncios::route('/'),
            'create' => CreateAnuncios::route('/create'),
            'edit' => EditAnuncios::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
