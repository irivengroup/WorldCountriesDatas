<?php

declare(strict_types=1);

namespace Iriven\Bridge\Symfony;

use Iriven\WorldDatasets\WorldDatasetsFactory;

final class CountriesExtension
{
    public static function create(?string $path = null): \Iriven\Countries
    {
        return WorldDatasetsFactory::make($path);
    }
}
