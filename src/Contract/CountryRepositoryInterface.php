<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;

use Iriven\WorldDatasets\Country;

interface CountryRepositoryInterface
{
    public function count(): int;

    /** @return list<Country> */
    public function findAll(): array;

    public function findOneByAlpha2(string $alpha2): ?Country;

    public function findOneByAlpha3(string $alpha3): ?Country;

    public function findOneByNumeric(string $numeric): ?Country;

    public function findOneByName(string $name): ?Country;

    /** @return list<Country> */
    public function findByName(string $name): array;

    /** @return list<Country> */
    public function search(string $term): array;

    /** @return list<Country> */
    public function findByCurrencyCode(string $currencyCode): array;

    /** @return list<Country> */
    public function findByRegion(string $region): array;

    /** @return list<Country> */
    public function findByPhoneCode(string $phoneCode): array;

    /** @return list<Country> */
    public function findByTld(string $tld): array;

    /** @return array<string, string> */
    public function getAllCurrenciesCodeAndName(): array;

    /** @return array<string, string> */
    public function getAllRegionsCodeAndName(): array;

    /** @return array<string, array<string, string>> */
    public function getAllCountriesGroupedByRegions(): array;

    /** @return array<string, array<string, string>> */
    public function getAllCountriesGroupedByCurrencies(): array;
}
