<?php

declare(strict_types=1);

namespace Iriven\ValueObject;

final class RegionInfo
{
    public function __construct(
        private readonly string $alphaCode,
        private readonly string $numericCode,
        private readonly string $name,
        private readonly string $subRegionCode,
        private readonly string $subRegionName,
    ) {
    }

    public function alphaCode(): string { return $this->alphaCode; }
    public function numericCode(): string { return $this->numericCode; }
    public function name(): string { return $this->name; }
    public function subRegionCode(): string { return $this->subRegionCode; }
    public function subRegionName(): string { return $this->subRegionName; }

    public function all(): array
    {
        return [
            'alpha_code' => $this->alphaCode,
            'numeric_code' => $this->numericCode,
            'name' => $this->name,
            'sub_region_code' => $this->subRegionCode,
            'sub_region_name' => $this->subRegionName,
        ];
    }
}
