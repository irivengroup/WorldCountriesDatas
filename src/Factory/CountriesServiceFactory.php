<?php

declare(strict_types=1);

namespace Iriven\Factory;

use Iriven\Infrastructure\Cache\ArrayCache;
use Iriven\Infrastructure\Cache\CacheInterface;
use Iriven\Infrastructure\Persistence\SqliteCountryRepository;
use Iriven\Support\CountryCodeNormalizer;
use Iriven\Support\NullLogger;
use Iriven\WorldCountriesDatas;
use Psr\Log\LoggerInterface;

final class CountriesServiceFactory
{
    public static function createFromSqlite(
        string $sqliteFilePath,
        ?CacheInterface $cache = null,
        ?LoggerInterface $logger = null,
        ?CountryCodeNormalizer $normalizer = null,
    ): WorldCountriesDatas {
        $repository = SqliteCountryRepository::fromSqliteFile(
            $sqliteFilePath,
            $cache ?? new ArrayCache(),
            $logger ?? new NullLogger(),
            $normalizer ?? new CountryCodeNormalizer(),
        );

        return new WorldCountriesDatas($repository);
    }
}
