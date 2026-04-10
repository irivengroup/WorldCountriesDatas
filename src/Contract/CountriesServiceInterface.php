<?php

declare(strict_types=1);

namespace Iriven\Contract;

use Countable;
use Generator;
use Iriven\Country;
use IteratorAggregate;
use Traversable;

interface CountriesServiceInterface extends Countable, IteratorAggregate
{
    public function all(): array;

    public function iterator(int|string $IsoFormat = 0): Generator;

    public function getIterator(): Traversable;

    public function getCountry(string $IsoCode): Country;

    public function country(string $IsoCode): Country;

    public function findCountry(string $IsoCode): ?Country;

    public function getCountryInfos(string $IsoCode, bool $dataOnly = false): array;

    public function getCountryData(string $IsoCode, bool $dataOnly = false): array;

    /** @return list<Country> */
    public function findByName(string $name): array;

    /** @return list<Country> */
    public function searchCountries(string $term): array;

    /** @return list<Country> */
    public function findByCurrencyCode(string $currencyCode): array;

    /** @return list<Country> */
    public function findByRegion(string $region): array;

    /** @return list<Country> */
    public function findByPhoneCode(string $phoneCode): array;

    /** @return list<Country> */
    public function findByTld(string $tld): array;
}
