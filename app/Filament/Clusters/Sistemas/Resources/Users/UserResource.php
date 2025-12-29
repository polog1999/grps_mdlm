<?php

namespace App\Filament\Clusters\Sistemas\Resources\Users;

use App\Filament\Clusters\Sistemas\Resources\Users\Pages\CreateUser;
use App\Filament\Clusters\Sistemas\Resources\Users\Pages\EditUser;
use App\Filament\Clusters\Sistemas\Resources\Users\Pages\ListUsers;
use App\Filament\Clusters\Sistemas\Resources\Users\Schemas\UserForm;
use App\Filament\Clusters\Sistemas\Resources\Users\Tables\UsersTable;
use App\Filament\Clusters\Sistemas\SistemasCluster;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'fas-users';

    protected static ?string $cluster = SistemasCluster::class;

    protected static ?string $recordTitleAttribute = 'User';

    protected static ?string $navigationLabel = 'Usuarios del sistema';
    protected static ?string $pluralModelLabel = 'Usuarios del sistema';
    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view::users') ?? false;
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
