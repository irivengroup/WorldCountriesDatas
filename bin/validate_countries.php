<?php

declare(strict_types=1);

use Iriven\WorldDatasets\components\WorldDatasets\WorldDatasetsFactory;
use Iriven\WorldDatasets\components\DatasetValidator;

require_once __DIR__ . '/../vendor/autoload.php';

$service = WorldDatasetsFactory::make();
$validator = new DatasetValidator();

$strict = ($argv[1] ?? '--strict') === '--strict';
$report = $validator->validate($service->countries()->values(), $strict);

echo json_encode($report->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
