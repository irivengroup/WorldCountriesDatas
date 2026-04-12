<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Application\Factory;
use Iriven\WorldDatasets\Application\Config\WorldDatasetsRuntimeConfig;
use Iriven\WorldDatasets\Application\WorldDatasetsService;

use Iriven\WorldDatasets\DataSource;
use Iriven\WorldDatasets\Infrastructure\Persistence\CsvCountryRepository;
use Iriven\WorldDatasets\Infrastructure\Persistence\JsonCountryRepository;
use Iriven\WorldDatasets\Domain\DatasetValidator;
use Iriven\WorldDatasets\Application\WorldDatasets;


use Iriven\WorldDatasets\Contract\CountryRepositoryInterface;
use Iriven\WorldDatasets\Contract\SimpleCacheInterface;
use Iriven\WorldDatasets\Infrastructure\Cache\ArrayCache;
use Iriven\WorldDatasets\Infrastructure\Persistence\SqliteCountryRepository;
use Iriven\WorldDatasets\Support\NullLogger;
use InvalidArgumentException;
use RuntimeException;

final class WorldDatasetsFactory
{
    /** @var array<string, mixed> */
    private static array $metaCache = [];

    public static function make(?string $sourcePath = null): WorldDatasetsService
    {
        return self::fromConfig(new WorldDatasetsRuntimeConfig(sourcePath: $sourcePath));
    }

    public static function fromConfig(WorldDatasetsRuntimeConfig $config, ?SimpleCacheInterface $cache = null): WorldDatasetsService
    {
        $path = $config->sourcePath() ?? self::defaultSqlitePath();

        if ($config->verifyChecksum()) {
            self::assertChecksum($path);
        }

        $repository = self::makeRepository($path, $cache);

        $service = new WorldDatasetsService(
            $repository,
            datasetSource: self::detectSourceName($path),
            datasetVersion: self::datasetVersion(),
            datasetChecksum: self::checksumFor($path),
            datasetBuiltAt: self::builtAt(),
        );

        if ($config->strictValidation()) {
            (new DatasetValidator())->validate($service->countries()->values(), true);
        }

        return $service;
    }

    public static function makeWithValidation(?string $sourcePath = null): WorldDatasetsService
    {
        return self::fromConfig(new WorldDatasetsRuntimeConfig(
            sourcePath: $sourcePath,
            verifyChecksum: true,
            strictValidation: true,
        ));
    }

    /**
     * @param SimpleCacheInterface|null $cache Reserved for future repository-level cache injection.
     */
    public static function makeRepository(string $path, ?SimpleCacheInterface $cache = null): CountryRepositoryInterface
    {
        $cache ??= null;
        $filename = basename($path);
        $extensionValue = pathinfo($path, PATHINFO_EXTENSION);
        $extension = strtolower((string) $extensionValue);

        return match (true) {
            $extension === 'json',
            $extension === 'jsonn',
            str_ends_with($filename, '.json'),
            str_ends_with($filename, '.jsonn') => new JsonCountryRepository($path),

            $extension === 'csv',
            str_ends_with($filename, '.csv') => new CsvCountryRepository($path),

            $extension === 'sqlite',
            $extension === 'db',
            str_ends_with($filename, '.sqlite'),
            str_ends_with($filename, '.db') => SqliteCountryRepository::fromSqliteFile(
                $path,
                new ArrayCache(),
                new NullLogger()
            ),

            default => throw new InvalidArgumentException(sprintf('Unsupported data source extension: %s', $extension)),
        };
    }

    public static function defaultSqlitePath(): string
    {
        return self::sourceDataDir() . '/' . DataSource::SQLITE;
    }

    public static function defaultJsonPath(): string
    {
        return self::sourceDataDir() . '/' . DataSource::JSON;
    }

    public static function defaultCsvPath(): string
    {
        return self::sourceDataDir() . '/' . DataSource::CSV;
    }

    public static function datasetMetaPath(): string
    {
        return self::sourceDataDir() . '/.countriesRepository.meta.json';
    }

    public static function datasetShaPath(): string
    {
        return self::sourceDataDir() . '/.countriesRepository.sha256';
    }

    public static function datasetVersion(): string
    {
        $data = self::metaData();
        return (string) ($data['dataset_version'] ?? 'unknown');
    }

    public static function builtAt(): ?string
    {
        $data = self::metaData();
        return isset($data['built_at']) ? (string) $data['built_at'] : null;
    }

    public static function checksumFor(string $path): ?string
    {
        return is_file($path) ? (hash_file('sha256', $path) ?: null) : null;
    }

    public static function assertChecksum(string $path): void
    {
        if (!is_file($path)) {
            throw new RuntimeException('Missing source file.');
        }

        $data = self::metaData();
        if (!isset($data['checksums'][basename($path)])) {
            throw new RuntimeException('Missing checksum entry for source file.');
        }

        $expected = (string) $data['checksums'][basename($path)];
        $actual = self::checksumFor($path);

        if ($actual !== $expected) {
            throw new RuntimeException(sprintf('Checksum mismatch for %s.', basename($path)));
        }
    }

    /** @return array<string, mixed> */
    private static function metaData(): array
    {
        if (self::$metaCache !== []) {
            return self::$metaCache;
        }

        $metaPath = self::datasetMetaPath();
        if (!is_file($metaPath)) {
            self::$metaCache = [];

            return self::$metaCache;
        }

        $data = json_decode((string) file_get_contents($metaPath), true);

        return self::$metaCache = is_array($data) ? $data : [];
    }

    private static function detectSourceName(string $path): string
    {
        $filename = basename($path);
        $extensionValue = pathinfo($path, PATHINFO_EXTENSION);
        $extension = strtolower((string) $extensionValue);

        return match (true) {
            $extension === 'json',
            $extension === 'jsonn',
            str_ends_with($filename, '.json'),
            str_ends_with($filename, '.jsonn') => 'json',

            $extension === 'csv',
            str_ends_with($filename, '.csv') => 'csv',

            $extension === 'sqlite',
            $extension === 'db',
            str_ends_with($filename, '.sqlite'),
            str_ends_with($filename, '.db') => 'sqlite',

            default => 'unknown',
        };
    }


    private static function sourceDataDir(): string
    {
        return dirname(__DIR__, 2) . '/data';
    }
}
