<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\Contract\CountryRepositoryInterface;

final class ArrayCountryRepository implements CountryRepositoryInterface
{
    public function __construct(
        private readonly array $countries,
    ) {
    }

    public function count(): int
    {
        return count($this->countries);
    }

    public function findAll(): array
    {
        return $this->countries;
    }

    public function findOneByAlpha2(string $alpha2): ?Country
    {
        foreach ($this->countries as $country) {
            if ($country->alpha2() === strtoupper(trim($alpha2))) {
                return $country;
            }
        }
        return null;
    }

    public function findOneByAlpha3(string $alpha3): ?Country
    {
        foreach ($this->countries as $country) {
            if ($country->alpha3() === strtoupper(trim($alpha3))) {
                return $country;
            }
        }
        return null;
    }

    public function findOneByNumeric(string $numeric): ?Country
    {
        foreach ($this->countries as $country) {
            if ($country->numeric() === trim($numeric)) {
                return $country;
            }
        }
        return null;
    }

    public function getAllCurrenciesCodeAndName(): array
    {
        return (new CurrenciesCollection($this->countries))->list();
    }

    public function getAllRegionsCodeAndName(): array
    {
        return (new RegionsCollection($this->countries))->list();
    }

    public function getAllCountriesGroupedByRegions(): array
    {
        return (new RegionsCollection($this->countries))->countries();
    }

    public function getAllCountriesGroupedByCurrencies(): array
    {
        return (new CurrenciesCollection($this->countries))->countries();
    }
}
