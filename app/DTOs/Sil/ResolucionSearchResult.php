<?php

namespace App\DTOs\Sil;

use Illuminate\Support\Collection;

class ResolucionSearchResult
{
    public const STATUS_FOUND = 'FOUND';
    public const STATUS_MULTIPLE_AREAS = 'MULTIPLE_AREAS';
    public const STATUS_NOT_FOUND = 'NOT_FOUND';

    public function __construct(
        public readonly string $status,
        public readonly ?array $data = null,       // Datos de la resolución (número, fecha, código único)
        public readonly ?array $areaMatches = null // Lista de áreas cuando hay múltiples
    ) {
    }

    public static function found(array $data): self
    {
        return new self(self::STATUS_FOUND, data: $data);
    }

    public static function multipleAreas(array $baseData, array $matches): self
    {
        return new self(self::STATUS_MULTIPLE_AREAS, data: $baseData, areaMatches: $matches);
    }

    public static function notFound(): self
    {
        return new self(self::STATUS_NOT_FOUND);
    }
}