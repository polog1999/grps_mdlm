<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Enums;

use Filament\Support\Contracts\HasLabel;

enum AsuntoAnuncio: string implements HasLabel
{
    case ASUNTO_PUBLICITARIO = 'ASUNTO PUBLICITARIO';
    case LETRAS_RECORTADAS = 'LETRAS RECORTADAS';

    public function getLabel(): ?string
    {
        return $this->value;
    }
}
