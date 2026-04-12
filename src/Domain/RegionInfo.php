<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Domain;

use Iriven\WorldDatasets\Contract\Arrayable;

final class RegionInfo implements Arrayable, \JsonSerializable
{
    public function __construct(
        private readonly string $alphaCode,
        private readonly string $numericCode,
        private readonly string $name,
        private readonly SubRegionInfo $subRegion,
    ) {
    }

    public function alphaCode(): string
    {
        return $this->alphaCode;
    }

    public function numericCode(): string
    {
        return $this->numericCode;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function subRegion(): SubRegionInfo
    {
        return $this->subRegion;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'alpha_code' => $this->alphaCode,
            'numeric_code' => $this->numericCode,
            'name' => $this->name,
            'sub_region' => $this->subRegion->toArray(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
