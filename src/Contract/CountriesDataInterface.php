<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Contract;

use Iriven\WorldDatasets\CountriesCollection;
use Iriven\WorldDatasets\CountryCodeFormat;

interface CountriesDataInterface
{
    public function all(): array;

    public function countries(int|string|CountryCodeFormat $format = CountryCodeFormat::ALPHA2): CountriesCollection;
}
