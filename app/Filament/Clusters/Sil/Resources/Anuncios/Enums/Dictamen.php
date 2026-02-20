<?php

namespace App\Filament\Clusters\Sil\Resources\Anuncios\Enums;

enum Dictamen: string
{
    case PROCEDENTE = 'PROCEDENTE';
    case IMPROCEDENTE = 'IMPROCEDENTE';
    case OBSERVADO = 'OBSERVADO';
}