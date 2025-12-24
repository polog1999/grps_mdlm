<?php

namespace App\Filament\Clusters\Sistemas\Resources\Permissions\Pages;

use App\Filament\Clusters\Sistemas\Resources\Permissions\PermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;
}
