<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;
use Iriven\WorldDatasets\Domain\CountriesCollection\CountryCodeFormat;
use Iriven\WorldDatasets\Application\WorldDatasets;


use Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\CurrencyCollection;
use Iriven\WorldDatasets\Domain\RegionCollection;
use Iriven\WorldDatasets\Domain\Country;

interface ReadonlyWorldDatasetsServiceInterface
{
    public function country(string $code): Country;

    public function findCountry(string $code): ?Country;

    public function countries(int|string|\Iriven\WorldDatasets\Domain\CountryCodeFormat $format = \Iriven\WorldDatasets\Domain\CountryCodeFormat::ALPHA2): CountriesCollection;

    public function currencies(): CurrencyCollection;

    public function regions(): RegionCollection;
}
