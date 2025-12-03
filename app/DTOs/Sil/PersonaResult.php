<?php

namespace App\DTOs\Sil;

use Illuminate\Support\Collection;

class PersonaResult
{
    public const STATUS_FOUND = 'FOUND';
    public const STATUS_NOT_FOUND = 'NOT_FOUND';

    public function __construct(
        public readonly string $status,
        public readonly ?array $data = null,
    ) {
    }

    public static function found(array $data): self
    {
        return new self(self::STATUS_FOUND, data: $data);
    }

    public static function notFound(): self
    {
        return new self(self::STATUS_NOT_FOUND);
    }
}