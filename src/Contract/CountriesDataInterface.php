<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;
use Iriven\WorldDatasets\components\WorldDatasets;


use Countable;
use IteratorAggregate;
use Iriven\WorldDatasets\components\CountriesCollection;
use Iriven\WorldDatasets\components\CurrencyCollection;
use Iriven\WorldDatasets\components\RegionCollection;
use Iriven\WorldDatasets\components\Country;
use Iriven\WorldDatasets\components\CountryCodeFormat;

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
