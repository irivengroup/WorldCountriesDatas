<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Bridge\Laravel;
use Iriven\WorldDatasets\Application\WorldDatasets;


use Illuminate\Support\ServiceProvider;
use Iriven\WorldDatasets\Application\Factory\WorldDatasetsFactory;
use Iriven\WorldDatasets\Application\WorldDatasetsService;

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
