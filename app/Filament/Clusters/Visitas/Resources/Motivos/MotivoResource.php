<?php

namespace App\Filament\Clusters\Visitas\Resources\Motivos;

use App\Filament\Clusters\Visitas\Resources\Motivos\Pages\CreateMotivo;
use App\Filament\Clusters\Visitas\Resources\Motivos\Pages\EditMotivo;
use App\Filament\Clusters\Visitas\Resources\Motivos\Pages\ListMotivos;
use App\Filament\Clusters\Visitas\Resources\Motivos\Schemas\MotivoForm;
use App\Filament\Clusters\Visitas\Resources\Motivos\Tables\MotivosTable;
use App\Filament\Clusters\Visitas\VisitasCluster;
use App\Models\Motivo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MotivoResource extends Resource
{
    protected static ?string $model = Motivo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = VisitasCluster::class;

    protected static ?string $recordTitleAttribute = 'Motivo';

    public static function form(Schema $schema): Schema
    {
        return MotivoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MotivosTable::configure($table);
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
            'index' => ListMotivos::route('/'),
            'create' => CreateMotivo::route('/create'),
            'edit' => EditMotivo::route('/{record}/edit'),
        ];
    }
}
