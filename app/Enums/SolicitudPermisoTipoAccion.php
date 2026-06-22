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
    case RECTIFICAR_LICENCIA = 'RECTIFICAR_LICENCIA';
    case CAMBIAR_GIRO_LICENCIA = 'CAMBIAR_GIRO_LICENCIA';

    case SUBIR_PDF_LICENCIA = 'SUBIR_PDF_LICENCIA';

    case SUBIR_PDF_COMPATIBILIDAD = 'SUBIR_PDF_COMPATIBILIDAD';

    case EDITAR_DATOS_ITSE = 'EDITAR_DATOS_ITSE';
    case ELIMINAR_ITSE = 'ELIMINAR_ITSE';
    case SUBIR_PDF_ITSE = 'SUBIR_PDF_ITSE';
    case SUBIR_PDF_ANEXOS = 'SUBIR_PDF_ANEXOS';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::EDITAR_DATOS_LICENCIA => 'Editar Datos de Licencia',
            self::DAR_BAJA_LICENCIA => 'Dar Baja a Licencia',
            self::DUPLICAR_LICENCIA => 'Duplicar Licencia',
            self::TRANSFERIR_LICENCIA => 'Transferir Licencia',
            self::CESIONAR_LICENCIA => 'Cesionar Licencia',
            self::RECTIFICAR_LICENCIA => 'Rectificar Licencia',
            self::CAMBIAR_GIRO_LICENCIA => 'Cambiar Giro Licencia',
            self::SUBIR_PDF_LICENCIA => 'Subir PDF de Licencia',
            self::SUBIR_PDF_COMPATIBILIDAD => 'Subir PDF de Compatibilidad',
            self::EDITAR_DATOS_ITSE => 'Editar Datos de ITSE',
            self::ELIMINAR_ITSE => 'Eliminar ITSE',
            self::SUBIR_PDF_ITSE => 'Subir PDF de ITSE',
            self::SUBIR_PDF_ANEXOS => 'Subir PDF de Anexos',
        };
    }
}
