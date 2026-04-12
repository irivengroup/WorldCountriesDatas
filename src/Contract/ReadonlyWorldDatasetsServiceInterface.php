<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;
use Iriven\WorldDatasets\Domain\CountriesCollection\CountryCodeFormat;

use Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\CurrencyCollection;
use Iriven\WorldDatasets\Domain\RegionCollection;
use Iriven\WorldDatasets\Domain\CountryInfo;

interface ReadonlyWorldDatasetsServiceInterface
{
    public function country(string $code): Country;

    public function findCountry(string $code): ?CountryInfo;

    public function countries(int|string|\Iriven\WorldDatasets\Domain\CountriesCollection\CountryCodeFormat $format = \Iriven\WorldDatasets\Domain\CountriesCollection\CountryCodeFormat::ALPHA2): CountriesCollection;

    public function currencies(): CurrencyCollection;

    public function regions(): RegionCollection;
}
