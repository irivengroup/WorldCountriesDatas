<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;

use Countable;
use IteratorAggregate;
use Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\CurrencyCollection;
use Iriven\WorldDatasets\Domain\RegionCollection;
use Iriven\WorldDatasets\Domain\CountryInfo;
use Iriven\WorldDatasets\Domain\CountriesCollection\CountryCodeFormat;

/**
 * @extends IteratorAggregate<int, CountryInfo>
 */
interface CountriesDataInterface extends Countable, IteratorAggregate
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array;

    public function country(string $code): CountryInfo;

    public function findCountry(string $code): ?CountryInfo;

    public function countries(int|string|CountryCodeFormat $format = CountryCodeFormat::ALPHA2): CountriesCollection;

    public function currencies(): CurrencyCollection;

    public function regions(): RegionCollection;
}
