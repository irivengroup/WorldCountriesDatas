<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets;

use Iriven\WorldDatasets\Contract\Arrayable;
use Iriven\WorldDatasets\Exporter\CsvExporter;
use Iriven\WorldDatasets\Exporter\JsonExporter;

final class RegionCollection implements Arrayable, \JsonSerializable
{
    /** @var array<int, RegionInfo>|null */
    private ?array $cachedValues = null;
    /** @var array<string, string>|null */
    private ?array $cachedList = null;
    /** @var array<string, array<string, string>>|null */
    private ?array $cachedCountries = null;
    /** @var array<int, array<string, mixed>>|null */
    private ?array $cachedExportArray = null;

    /**
     * @param array<int, Country> $countries
     */
    public function __construct(
        private readonly array $countries,
    ) {
    }

    /** @return array<int, RegionInfo> */
    public function values(): array
    {
        if ($this->cachedValues !== null) {
            return $this->cachedValues;
        }

        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->numericCode() === '' && $region->name() === '') {
                continue;
            }
            $result[$region->numericCode() . '|' . $region->name()] = $region;
        }
        ksort($result);

        return $this->cachedValues = array_values($result);
    }

    /** @return array<string, string> */
    public function list(): array
    {
        if ($this->cachedList !== null) {
            return $this->cachedList;
        }

        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->numericCode() === '') {
                continue;
            }
            $result[$region->numericCode()] = $region->name();
        }
        asort($result);

        return $this->cachedList = $result;
    }

    /** @return array<string, array<string, string>> */
    public function countries(): array
    {
        if ($this->cachedCountries !== null) {
            return $this->cachedCountries;
        }

        $result = [];
        foreach ($this->countries as $country) {
            $region = $country->region();
            if ($region->name() === '') {
                continue;
            }
            $result[$region->name()][$country->alpha2()] = $country->name();
        }
        ksort($result);

        return $this->cachedCountries = $result;
    }

    /** @return array<int, array<string, mixed>> */
    public function exportArray(): array
    {
        if ($this->cachedExportArray !== null) {
            return $this->cachedExportArray;
        }

        $result = [];
        foreach ($this->values() as $region) {
            $result[] = $region->toArray();
        }

        return $this->cachedExportArray = $result;
    }

    public function toJson(int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE): string
    {
        return (new JsonExporter())->export($this->exportArray(), $flags);
    }

    public function toCsv(): string
    {
        return (new CsvExporter())->export($this->exportArray());
    }

    public function exportJsonFile(string $path): void
    {
        (new JsonExporter())->exportFile($path, $this->exportArray());
    }

    public function exportCsvFile(string $path): void
    {
        (new CsvExporter())->exportFile($path, $this->exportArray());
    }

    /** @return array<int, array<string, mixed>> */
    public function toArray(): array { return $this->exportArray(); }
    /** @return array<int, array<string, mixed>> */
    public function jsonSerialize(): array { return $this->exportArray(); }
}
