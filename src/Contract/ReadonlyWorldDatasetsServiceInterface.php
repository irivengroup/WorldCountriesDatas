<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;

use Iriven\WorldDatasets\CountriesCollection;
use Iriven\WorldDatasets\CurrencyCollection;
use Iriven\WorldDatasets\RegionCollection;
use Iriven\WorldDatasets\Country;

interface ReadonlyWorldDatasetsServiceInterface
{
    public function country(string $code): Country;

    public function findCountry(string $code): ?Country;

    public function countries(int|string|\Iriven\WorldDatasets\CountryCodeFormat $format = \Iriven\WorldDatasets\CountryCodeFormat::ALPHA2): CountriesCollection;

    public function currencies(): CurrencyCollection;

    public function regions(): RegionCollection;
}
