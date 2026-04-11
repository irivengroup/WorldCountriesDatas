<?php

declare(strict_types=1);

namespace Iriven;

final class CountriesConfig
{
    public function __construct(
        private readonly string $sqlitePath,
        private readonly bool $strictMode = true,
        private readonly CountryCodeFormat $defaultFormat = CountryCodeFormat::ALPHA2,
        private readonly bool $cacheEnabled = true,
        private readonly string $datasetSource = 'sqlite',
        private readonly string $datasetVersion = '1.1.0',
    ) {
    }

    public function sqlitePath(): string
    {
        return $this->sqlitePath;
    }

    public function strictMode(): bool
    {
        return $this->strictMode;
    }

    public function defaultFormat(): CountryCodeFormat
    {
        return $this->defaultFormat;
    }

    public function cacheEnabled(): bool
    {
        return $this->cacheEnabled;
    }

    public function datasetSource(): string
    {
        return $this->datasetSource;
    }

    public function datasetVersion(): string
    {
        return $this->datasetVersion;
    }
}
