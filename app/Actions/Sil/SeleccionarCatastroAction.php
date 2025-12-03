<?php

namespace App\Actions\Sil;

class SeleccionarCatastroAction
{
    public function execute(array $datosActuales, array $coincidencias, string $selectedId): array
    {
        // 1. Buscar el objeto seleccionado
        $seleccionado = null;
        foreach ($coincidencias as $item) {
            $fiuId = is_object($item) ? ($item->fiu_id ?? null) : ($item['fiu_id'] ?? null);
            if ((string) $fiuId === (string) $selectedId) {
                $seleccionado = is_object($item) ? (array) $item : $item;
                break;
            }
        }

        if (!$seleccionado) {
            return $datosActuales; // No se encontró, no hacemos cambios
        }

        // 2. Actualizar el estado
        $datosActuales['catastro'] = $seleccionado;

        return $datosActuales;
    }
}