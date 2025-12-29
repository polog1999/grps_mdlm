<?php

namespace App\Filament\Clusters\Sistemas\Resources\Permissions;

use App\Filament\Clusters\Sistemas\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Clusters\Sistemas\Resources\Permissions\Pages\EditPermission;
use App\Filament\Clusters\Sistemas\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Clusters\Sistemas\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Clusters\Sistemas\Resources\Permissions\Tables\PermissionsTable;
use App\Filament\Clusters\Sistemas\SistemasCluster;
use App\Models\Permission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';

    protected static ?string $cluster = SistemasCluster::class;

    protected static ?string $recordTitleAttribute = 'Permission';

    protected static ?string $navigationLabel = 'Permisos del sistema';
    protected static ?string $pluralModelLabel = 'Permisos del sistema';

    public static function form(Schema $schema): Schema
    {
        return PermissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PermissionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canAccess(): bool
    {
        return auth()->user()?->can('view::permissions') ?? false;
    }
    public static function getPages(): array
    {
        return [
            'index' => ListPermissions::route('/'),
            //'create' => CreatePermission::route('/create'),
            //'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}
