<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\Country;
use Iriven\WorldDatasets\Domain\Country\CountryArrayTransformer;

final class CountriesCollectionExporter
{
    public function __construct(
        private readonly CountryArrayTransformer $transformer = new CountryArrayTransformer(),
    ) {
    }

    /**
     * @param array<int, Country> $countries
     * @return array<int, array<string, mixed>>
     */
    public function toApiArray(array $countries): array
    {
        $result = [];
        foreach ($countries as $country) {
            $result[] = $this->transformer->toApiArray($country);
        }
        return array_values($result);
    }

    /**
     * @param array<int, Country> $countries
     * @return array<int, array<int, string>>
     */
    public function toStorageArray(array $countries): array
    {
        $result = [];
        foreach ($countries as $country) {
            $result[] = $this->transformer->toStorageArray($country);
        }
        return array_values($result);
    }
}
