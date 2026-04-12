<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Bridge\Symfony;
use Iriven\WorldDatasets\Application\WorldDatasets;
use Iriven\WorldDatasets\Application\WorldDatasetsService;


use Iriven\WorldDatasets\Application\Factory\WorldDatasetsFactory;

final class CountriesExtension
{
    public static function create(?string $path = null): \Iriven\WorldDatasets\Application\WorldDatasetsService
    {
        return WorldDatasetsFactory::make($path);
    }
}
