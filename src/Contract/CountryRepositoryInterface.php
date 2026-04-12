<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;

use Iriven\WorldDatasets\Domain\CountryInfo;

interface CountryRepositoryInterface
{
    public function count(): int;

    /** @return array<int, CountryInfo> */
    public function findAll(): array;

    public function findOneByAlpha2(string $alpha2): ?CountryInfo;

    public function findOneByAlpha3(string $alpha3): ?CountryInfo;

    public function findOneByNumeric(string $numeric): ?CountryInfo;

    public function findOneByName(string $name): ?CountryInfo;

    /** @return array<int, CountryInfo> */
    public function findByName(string $name): array;

    /** @return array<int, CountryInfo> */
    public function search(string $term): array;

    /** @return array<int, CountryInfo> */
    public function findByCurrencyCode(string $currencyCode): array;

    /** @return array<int, CountryInfo> */
    public function findByRegion(string $region): array;

    /** @return array<int, CountryInfo> */
    public function findByPhoneCode(string $phoneCode): array;

    /** @return array<int, CountryInfo> */
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
