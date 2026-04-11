<?php

declare(strict_types=1);

namespace Iriven;

final class CountriesStats implements \JsonSerializable
{
    public function __construct(
        private readonly int $total,
        private readonly int $regions,
        private readonly int $currencies,
    ) {
    }

    public function total(): int
    {
        return $this->total;
    }

    public function regions(): int
    {
        return $this->regions;
    }

    public function currencies(): int
    {
        return $this->currencies;
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'regions' => $this->regions,
            'currencies' => $this->currencies,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
