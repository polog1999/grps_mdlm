<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum NavigationGroup: string implements HasLabel
{
    case COMPATIBILIDAD = 'Compatibilidad';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
