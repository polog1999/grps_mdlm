<?php

namespace App\Filament\Clusters\Sil\Resources\Personas;

use App\Filament\Clusters\Sil\Resources\Personas\Pages\CreatePersona;
use App\Filament\Clusters\Sil\Resources\Personas\Pages\EditPersona;
use App\Filament\Clusters\Sil\Resources\Personas\Pages\ListPersonas;
use App\Filament\Clusters\Sil\Resources\Personas\Schemas\PersonaForm;
use App\Filament\Clusters\Sil\Resources\Personas\Tables\PersonasTable;
use App\Filament\Clusters\Sil\SilCluster;
use App\Models\Persona;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PersonaResource extends Resource
{
    protected static ?string $model = Persona::class;

    protected static string|BackedEnum|null $navigationIcon = 'ik-person';

    protected static ?string $cluster = SilCluster::class;

    protected static ?string $recordTitleAttribute = 'Persona';

    public static function form(Schema $schema): Schema
    {
        return PersonaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonasTable::configure($table);
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
            'index' => ListPersonas::route('/'),
            //'create' => CreatePersona::route('/create'),
            //'edit' => EditPersona::route('/{record}/edit'),
        ];
    }
}
