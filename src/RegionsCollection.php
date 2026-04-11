<?php

declare(strict_types=1);

namespace Iriven;

final class RegionsCollection
{
    public function __construct(
        private readonly array $countries,
    ) {
    }

    public function list(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->numericCode() === '') {
                continue;
            }
            $result[$region->numericCode()] = $region->name();
        }

        asort($result);

        return $result;
    }

    public function countries(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->name() === '') {
                continue;
            }
            $result[$region->name()][$country->alpha2()] = $country->name();
        }

        ksort($result);

        return $result;
    }
}
