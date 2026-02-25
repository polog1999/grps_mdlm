<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Enums;

use Filament\Support\Contracts\HasLabel;

enum VigenciaAnuncio: string implements HasLabel
{
    case TEMPORAL = 'TEMPORAL';
    case INDETERMINADA = 'INDETERMINADA';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
