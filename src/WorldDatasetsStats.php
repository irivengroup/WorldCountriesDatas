<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets;

final class WorldDatasetsStats implements \JsonSerializable
{
    public function __construct(
        private readonly int $total,
        private readonly int $regions,
        private readonly int $currencies,
    ) {
    }

    public function total(): int { return $this->total; }
    public function regions(): int { return $this->regions; }
    public function currencies(): int { return $this->currencies; }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'regions' => $this->regions,
            'currencies' => $this->currencies,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
