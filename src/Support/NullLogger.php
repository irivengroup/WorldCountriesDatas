<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Support;
use Iriven\WorldDatasets\components\WorldDatasets;


use Psr\Log\AbstractLogger;

final class NullLogger extends AbstractLogger
{
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        // no-op
    }
}
