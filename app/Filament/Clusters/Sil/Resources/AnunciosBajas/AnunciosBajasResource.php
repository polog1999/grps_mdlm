<?php

namespace App\Filament\Clusters\Sil\Resources\AnunciosBajas;

use App\Filament\Clusters\Sil\Resources\AnunciosBajas\Pages\CreateAnunciosBajas;
use App\Filament\Clusters\Sil\Resources\AnunciosBajas\Pages\EditAnunciosBajas;
use App\Filament\Clusters\Sil\Resources\AnunciosBajas\Pages\ListAnunciosBajas;
use App\Filament\Clusters\Sil\Resources\AnunciosBajas\Pages\ViewAnunciosBajas;
use App\Filament\Clusters\Sil\Resources\AnunciosBajas\Schemas\AnunciosBajasForm;
use App\Filament\Clusters\Sil\Resources\AnunciosBajas\Schemas\AnunciosBajasInfolist;
use App\Filament\Clusters\Sil\Resources\AnunciosBajas\Tables\AnunciosBajasTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\AnuncioBaja;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AnunciosBajasResource extends Resource
{
    protected static ?string $model = AnuncioBaja::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $cluster = SilCluster::class;

    protected static ?int $navigationSort = 5;

    protected static string|UnitEnum|null $navigationGroup = 'Auditoría';

    protected static ?string $recordTitleAttribute = 'AnuncioBaja';

    public static function form(Schema $schema): Schema
    {
        return AnunciosBajasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AnunciosBajasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnunciosBajasTable::configure($table);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view::anuncios_bajas') ?? false;
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
            'index' => ListAnunciosBajas::route('/'),
            // 'create' => CreateAnunciosBajas::route('/create'),
            // 'view' => ViewAnunciosBajas::route('/{record}'),
            // 'edit' => EditAnunciosBajas::route('/{record}/edit'),
        ];
    }
}
