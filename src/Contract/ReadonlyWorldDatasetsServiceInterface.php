<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;
use Iriven\WorldDatasets\components\CountriesCollection\CountryCodeFormat;
use Iriven\WorldDatasets\components\WorldDatasets;


use Iriven\WorldDatasets\components\CountriesCollection;
use Iriven\WorldDatasets\components\CurrencyCollection;
use Iriven\WorldDatasets\components\RegionCollection;
use Iriven\WorldDatasets\components\Country;

interface ReadonlyWorldDatasetsServiceInterface
{
    public function country(string $code): Country;

    public function findCountry(string $code): ?Country;

    public function countries(int|string|\Iriven\WorldDatasets\components\CountryCodeFormat $format = \Iriven\WorldDatasets\components\CountryCodeFormat::ALPHA2): CountriesCollection;

    public function currencies(): CurrencyCollection;

    public function regions(): RegionCollection;
}
