<?php
// app/Actions/Sil/SeleccionarAreaResolucionAction.php

namespace App\Actions\Sil;

class SeleccionarAreaResolucionAction
{
    public function execute(array $datosActuales, array $coincidencias, string $selectedId): array
    {
        $seleccionado = null;
        foreach ($coincidencias as $item) {
            $codigo = is_object($item) ? ($item->codigo_unico_tramite ?? null) : ($item['codigo_unico_tramite'] ?? null);
            if ((string) $codigo === (string) $selectedId) {
                $seleccionado = is_object($item) ? (array) $item : $item;
                break;
            }
        }

        if (!$seleccionado) {
            return $datosActuales;
        }

        // 2. Preparar el array de resolución (Evitar Wipeout)
        $resolucionData = $datosActuales['resolucion'] ?? [];

        // 3. Inyectar SOLO el dato necesario (Merge)
        $resolucionData['codigo_unico_tramite'] = $seleccionado['codigo_unico_tramite'] ?? '';

        // 4. Guardar de vuelta
        $datosActuales['resolucion'] = $resolucionData;

        return $datosActuales;
    }
}