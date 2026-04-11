<?php

declare(strict_types=1);

namespace Iriven\Contract;

use Countable;
use IteratorAggregate;
use Iriven\CountriesCollection;
use Iriven\CurrenciesCollection;
use Iriven\RegionsCollection;
use Iriven\Country;

interface CountriesDataInterface extends Countable, IteratorAggregate
{
    public function all(): array;

    public function country(string $code): Country;

    public function findCountry(string $code): ?Country;

    public function countries(int|string|\Iriven\CountryCodeFormat $format = \Iriven\CountryCodeFormat::ALPHA2): CountriesCollection;

    public function currencies(): CurrenciesCollection;

    public function regions(): RegionsCollection;
}
