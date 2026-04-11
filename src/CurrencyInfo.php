<?php

declare(strict_types=1);

namespace Iriven;

final class CurrencyInfo
{
    public function __construct(
        private readonly string $code,
        private readonly string $name,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}
