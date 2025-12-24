<?php

namespace App\Filament\Clusters\Sistemas\Resources\Modules;

use App\Filament\Clusters\Sistemas\Resources\Modules\Pages\CreateModule;
use App\Filament\Clusters\Sistemas\Resources\Modules\Pages\EditModule;
use App\Filament\Clusters\Sistemas\Resources\Modules\Pages\ListModules;
use App\Filament\Clusters\Sistemas\Resources\Modules\Schemas\ModuleForm;
use App\Filament\Clusters\Sistemas\Resources\Modules\Tables\ModulesTable;
use App\Filament\Clusters\Sistemas\SistemasCluster;
use App\Models\Module;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $cluster = SistemasCluster::class;

    protected static ?string $recordTitleAttribute = 'Module';

    protected static ?string $navigationLabel = 'Modulos del sistema';
    protected static ?string $pluralModelLabel = 'Modulos del sistema';
    public static function form(Schema $schema): Schema
    {
        return ModuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModulesTable::configure($table);
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
            'index' => ListModules::route('/'),
            //'create' => CreateModule::route('/create'),
            //'edit' => EditModule::route('/{record}/edit'),
        ];
    }
}
