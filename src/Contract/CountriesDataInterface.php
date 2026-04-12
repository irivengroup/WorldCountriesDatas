<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;
use Iriven\WorldDatasets\Application\WorldDatasets;


use Countable;
use IteratorAggregate;
use Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\CurrencyCollection;
use Iriven\WorldDatasets\Domain\RegionCollection;
use Iriven\WorldDatasets\Domain\Country;
use Iriven\WorldDatasets\Domain\CountryCodeFormat;

/**
 * @extends IteratorAggregate<int, Country>
 */
interface CountriesDataInterface extends Countable, IteratorAggregate
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array;

    public function country(string $code): Country;

    public function findCountry(string $code): ?Country;

    public function countries(int|string|CountryCodeFormat $format = CountryCodeFormat::ALPHA2): CountriesCollection;

    public function currencies(): CurrencyCollection;

    public function regions(): RegionCollection;
}
