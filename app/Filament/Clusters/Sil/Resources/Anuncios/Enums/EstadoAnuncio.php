<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Enums;

use Filament\Support\Contracts\HasLabel;

enum EstadoAnuncio: string implements HasLabel
{
    case VIGENTE = 'VIGENTE';

    case BAJA = 'BAJA';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
