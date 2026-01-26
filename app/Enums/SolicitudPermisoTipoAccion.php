<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SolicitudPermisoTipoAccion: string implements HasLabel
{
    case EDITAR_DATOS_LICENCIA = 'EDITAR_DATOS_LICENCIA';
    case DAR_BAJA_LICENCIA = 'DAR_BAJA_LICENCIA';
    case DUPLICAR_LICENCIA = 'DUPLICAR_LICENCIA';
    case TRANSFERIR_LICENCIA = 'TRANSFERIR_LICENCIA';
    case CESIONAR_LICENCIA = 'CESIONAR_LICENCIA';

    case SUBIR_PDF_LICENCIA = 'SUBIR_PDF_LICENCIA';

    case SUBIR_PDF_COMPATIBILIDAD = 'SUBIR_PDF_COMPATIBILIDAD';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::EDITAR_DATOS_LICENCIA => 'Editar Datos de Licencia',
            self::DAR_BAJA_LICENCIA => 'Dar Baja a Licencia',
            self::DUPLICAR_LICENCIA => 'Duplicar Licencia',
            self::TRANSFERIR_LICENCIA => 'Transferir Licencia',
            self::CESIONAR_LICENCIA => 'Cesionar Licencia',
            self::SUBIR_PDF_LICENCIA => 'Subir PDF de Licencia',
            self::SUBIR_PDF_COMPATIBILIDAD => 'Subir PDF de Compatibilidad',
        };
    }
}
