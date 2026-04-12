<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Bridge\Symfony;
use Iriven\WorldDatasets\components\WorldDatasets;
use Iriven\WorldDatasets\components\WorldDatasets\WorldDatasetsService;


use Iriven\WorldDatasets\components\WorldDatasets\WorldDatasetsFactory;

final class CountriesExtension
{
    public static function create(?string $path = null): \Iriven\WorldDatasets\components\WorldDatasets\WorldDatasetsService
    {
        return WorldDatasetsFactory::make($path);
    }
}
