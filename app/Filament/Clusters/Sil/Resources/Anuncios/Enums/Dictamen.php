<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Enums;

use Filament\Support\Contracts\HasLabel;

enum Dictamen: string implements HasLabel
{
    case PROCEDENTE = 'PROCEDENTE';

    case IMPROCEDENTE = 'IMPROCEDENTE';
    case OBSERVADO = 'OBSERVADO';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}