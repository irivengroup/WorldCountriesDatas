<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Exporter;
use Iriven\WorldDatasets\components\WorldDatasets;


use Iriven\WorldDatasets\Exception\ExportException;

final class JsonExporter
{
    /**
     * @param array<mixed> $payload
     */
    public function export(array $payload, int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE): string
    {
        return (string) json_encode($payload, $flags);
    }

    /**
     * @param array<mixed> $payload
     */
    public function exportFile(string $path, array $payload, int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE): void
    {
        if (file_put_contents($path, $this->export($payload, $flags)) === false) {
            throw new ExportException(sprintf('Unable to write JSON file: %s', $path));
        }
    }
}
