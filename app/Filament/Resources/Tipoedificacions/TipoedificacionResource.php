<?php
// ...existing code...

namespace App\Filament\Resources\Tipoedificacions;

use App\Filament\Resources\Tipoedificacions\Pages\CreateTipoedificacion;
use App\Filament\Resources\Tipoedificacions\Pages\EditTipoedificacion;
use App\Filament\Resources\Tipoedificacions\Pages\ListTipoedificacions;
use App\Filament\Resources\Tipoedificacions\Schemas\TipoedificacionForm;
use App\Filament\Resources\Tipoedificacions\Tables\TipoedificacionsTable;
use App\Models\Tipoedificacion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TipoedificacionResource extends Resource
{
    protected static ?string $model = Tipoedificacion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    // usar un campo real como título del registro
    protected static ?string $recordTitleAttribute = 'tie_descripcion';

    public static function form(Schema $schema): Schema
    {
        return TipoedificacionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TipoedificacionsTable::configure($table);
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
            'index' => ListTipoedificacions::route('/'),
            'create' => CreateTipoedificacion::route('/create'),
            'edit' => EditTipoedificacion::route('/{record}/edit'),
        ];
    }
}