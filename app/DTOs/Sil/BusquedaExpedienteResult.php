<?php

namespace App\DTOs\Sil;

class BusquedaExpedienteResult
{
    // Estados posibles del proceso global
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_NOT_FOUND = 'NOT_FOUND';
    public const STATUS_SELECTION_CATASTRO = 'SELECTION_CATASTRO';
    public const STATUS_SELECTION_RESOLUCION = 'SELECTION_RESOLUCION';
    public const STATUS_SELECTION_ITSE = 'SELECTION_ITSE';

    public const STATUS_MISSING_PERSONA = 'MISSING_PERSONA';
    public const STATUS_ERROR = 'ERROR';

    public function __construct(
        public readonly string $status,
        public readonly array $data = [],          // Datos acumulados (expediente + catastro + resolucion)
        public readonly array $matches = [],       // Coincidencias para selección (ya sea catastro o áreas)
        public readonly ?string $message = null
    ) {
    }

    // Factory methods para claridad semántica
    public static function success(array $data): self
    {
        return new self(self::STATUS_SUCCESS, data: $data);
    }

    public static function notFound(string $msg = 'No se encontraron datos'): self
    {
        return new self(self::STATUS_NOT_FOUND, message: $msg);
    }

    public static function requireCatastroSelection(array $data, array $matches): self
    {
        return new self(self::STATUS_SELECTION_CATASTRO, data: $data, matches: $matches, message: 'Múltiples registros catastrales.');
    }

    public static function requireResolucionSelection(array $data, array $matches): self
    {
        return new self(self::STATUS_SELECTION_RESOLUCION, data: $data, matches: $matches, message: 'Múltiples áreas de resolución.');
    }

    public static function requirePersonaSearch(array $data): self
    {
        return new self(self::STATUS_MISSING_PERSONA, data: $data, message: 'El expediente no tiene una persona vinculada. Debe buscarla manualmente.');
    }

    public static function requireItseSelection(array $data, array $matches): self
    {
        return new self(
            status: self::STATUS_SELECTION_ITSE,
            data: $data,
            matches: $matches,
            message: 'Expediente de riesgo ALTO/MUY ALTO. Debe seleccionar una ITSE existente para continuar.'
        );
    }
}