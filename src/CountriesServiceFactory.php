<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\Infrastructure\Cache\ArrayCache;
use Iriven\Infrastructure\Persistence\SqliteCountryRepository;
use Iriven\Support\NullLogger;

final class CountriesServiceFactory
{
    public static function make(string $sqliteFilePath): Countries
    {
        $repository = SqliteCountryRepository::fromSqliteFile(
            $sqliteFilePath,
            new ArrayCache(),
            new NullLogger()
        );

        return new Countries($repository);
    }
}
