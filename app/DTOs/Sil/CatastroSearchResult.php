<?php

namespace App\DTOs\Sil;

use Illuminate\Support\Collection;

class CatastroSearchResult
{
    public const STATUS_FOUND = 'FOUND';
    public const STATUS_MULTIPLE = 'MULTIPLE';
    public const STATUS_NOT_FOUND = 'NOT_FOUND';

    public function __construct(
        public readonly string $status,
        public readonly ?array $data = null,
        public readonly ?array $matches = null
    ) {
    }

    public static function found(array $data): self
    {
        return new self(self::STATUS_FOUND, data: $data);
    }

    public static function multiple(array $matches): self
    {
        return new self(self::STATUS_MULTIPLE, matches: $matches);
    }

    public static function notFound(): self
    {
        return new self(self::STATUS_NOT_FOUND);
    }
}