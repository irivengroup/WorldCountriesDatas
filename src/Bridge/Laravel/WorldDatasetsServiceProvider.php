<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Bridge\Laravel;
use Iriven\WorldDatasets\components\WorldDatasets;


use Illuminate\Support\ServiceProvider;
use Iriven\WorldDatasets\components\WorldDatasets\WorldDatasetsFactory;
use Iriven\WorldDatasets\components\WorldDatasets\WorldDatasetsService;

final class WorldDatasetsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(WorldDatasetsService::class, function () {
            $path = base_path('vendor/irivengroup/world-datasets/src/data/.countriesRepository.sqlite');
            return WorldDatasetsFactory::make($path);
        });
    }
}
