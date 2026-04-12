#!/usr/bin/env php
<?php

declare(strict_types=1);

use Iriven\WorldDatasets\Application\Factory\WorldDatasetsFactory;
use Iriven\WorldDatasets\Domain\DatasetValidator;

require_once __DIR__ . '/../vendor/autoload.php';

$service = WorldDatasetsFactory::make();
$validator = new DatasetValidator();
$report = $validator->validate($service->countries()->values(), false);

echo json_encode($report->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
