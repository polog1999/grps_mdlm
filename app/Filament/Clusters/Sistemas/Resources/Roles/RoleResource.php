<?php

namespace App\Filament\Clusters\Sistemas\Resources\Roles;

use App\Filament\Clusters\Sistemas\Resources\Roles\Pages\CreateRole;
use App\Filament\Clusters\Sistemas\Resources\Roles\Pages\EditRole;
use App\Filament\Clusters\Sistemas\Resources\Roles\Pages\ListRoles;
use App\Filament\Clusters\Sistemas\Resources\Roles\Schemas\RoleForm;
use App\Filament\Clusters\Sistemas\Resources\Roles\Tables\RolesTable;
use App\Filament\Clusters\Sistemas\SistemasCluster;
use App\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-identification';

    protected static ?string $cluster = SistemasCluster::class;

    protected static ?string $recordTitleAttribute = 'Role';

    protected static ?string $navigationLabel = 'Roles del sistema';
    protected static ?string $pluralModelLabel = 'Roles del sistema';
    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->can('view_roles') ?? false;
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
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
