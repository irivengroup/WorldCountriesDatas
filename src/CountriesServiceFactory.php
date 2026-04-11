<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\Infrastructure\Cache\ArrayCache;
use Iriven\Infrastructure\Persistence\SqliteCountryRepository;
use Iriven\Support\NullLogger;

final class CountriesServiceFactory
{
    public static function make(?string $sqliteFilePath = null): Countries
    {
        $path = $sqliteFilePath ?? __DIR__ . '/data/countries.sqlite';

        $repository = SqliteCountryRepository::fromSqliteFile(
            $path,
            new ArrayCache(),
            new NullLogger()
        );

        return new Countries($repository);
    }
}
