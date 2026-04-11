<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\Contract\Arrayable;
use Iriven\Exception\ExportException;

final class RegionsCollection implements Arrayable, \JsonSerializable
{
    public function __construct(
        private readonly array $countries,
    ) {
    }

    public function values(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->numericCode() === '' && $region->name() === '') {
                continue;
            }
            $result[$region->numericCode() . '|' . $region->name()] = $region;
        }
        ksort($result);
        return array_values($result);
    }

    public function list(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->numericCode() === '') {
                continue;
            }
            $result[$region->numericCode()] = $region->name();
        }
        asort($result);
        return $result;
    }

    public function countries(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->name() === '') {
                continue;
            }
            $result[$region->name()][$country->alpha2()] = $country->name();
        }
        ksort($result);
        return $result;
    }

    public function exportArray(): array
    {
        return array_map(static fn(RegionInfo $region): array => $region->toArray(), $this->values());
    }

    public function toJson(int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE): string
    {
        return (string) json_encode($this->exportArray(), $flags);
    }

    public function toCsv(): string
    {
        $rows = $this->exportArray();
        if ($rows === []) {
            return '';
        }
        $stream = fopen('php://temp', 'r+');
        fputcsv($stream, ['alpha_code', 'numeric_code', 'name', 'sub_region']);
        foreach ($rows as $row) {
            fputcsv($stream, [$row['alpha_code'], $row['numeric_code'], $row['name'], json_encode($row['sub_region'], JSON_UNESCAPED_UNICODE)]);
        }
        rewind($stream);
        return (string) stream_get_contents($stream);
    }

    public function exportJsonFile(string $path): void
    {
        if (file_put_contents($path, $this->toJson()) === false) {
            throw new ExportException(sprintf('Unable to write JSON file: %s', $path));
        }
    }

    public function exportCsvFile(string $path): void
    {
        if (file_put_contents($path, $this->toCsv()) === false) {
            throw new ExportException(sprintf('Unable to write CSV file: %s', $path));
        }
    }

    public function toArray(): array { return $this->exportArray(); }
    public function jsonSerialize(): array { return $this->exportArray(); }
}
