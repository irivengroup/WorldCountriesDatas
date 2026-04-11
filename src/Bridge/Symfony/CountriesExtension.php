<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Bridge\Symfony;

use Iriven\WorldDatasets\WorldDatasetsFactory;

final class CountriesExtension
{
    public static function create(?string $path = null): \Iriven\WorldDatasets\WorldDatasetsService
    {
        return WorldDatasetsFactory::make($path);
    }
}
