<?php

declare(strict_types=1);

namespace Iriven;

final class RegionInfo
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

    public function toArray(): array
    {
        return [
            'alpha_code' => $this->alphaCode,
            'numeric_code' => $this->numericCode,
            'name' => $this->name,
            'sub_region' => $this->subRegion->toArray(),
        ];
    }
}
