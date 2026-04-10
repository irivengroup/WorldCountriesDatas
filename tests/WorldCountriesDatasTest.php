<?php

declare(strict_types=1);

namespace Iriven\Tests;

use Iriven\Infrastructure\Cache\ArrayCache;
use Iriven\Infrastructure\Persistence\SqliteCountryRepository;
use Iriven\Support\NullLogger;
use Iriven\WorldCountriesDatas;
use PHPUnit\Framework\TestCase;

final class WorldCountriesDatasTest extends TestCase
{
    private WorldCountriesDatas $service;

    protected function setUp(): void
    {
        $dbFile = __DIR__ . '/../data/countries.sqlite';

        $repository = SqliteCountryRepository::fromSqliteFile(
            $dbFile,
            new ArrayCache(),
            new NullLogger()
        );

        $this->service = new WorldCountriesDatas($repository);
    }

    public function testCanResolveFranceFromAlpha2(): void
    {
        self::assertSame('France', $this->service->getCountry('FR')->name());
    }

    public function testCanResolveFranceFromAlpha3(): void
    {
        self::assertSame('FRA', $this->service->getCountry('FR')->alpha3());
        self::assertSame('250', $this->service->getCountry('FRA')->numeric());
    }

    public function testCanResolveFranceFromNumeric(): void
    {
        self::assertSame('FR', $this->service->getCountry('250')->alpha2());
    }

    public function testGetCountryDataReturnsAssociativeArray(): void
    {
        $infos = $this->service->getCountryData('FR');

        self::assertSame('France', $infos['country']);
        self::assertSame('FRA', $infos['alpha3']);
        self::assertArrayHasKey('currency_code', $infos);
    }

    public function testGetAllCountriesCodeAndNameAlpha2(): void
    {
        $all = $this->service->getAllCountriesCodeAndName('alpha2');

        self::assertArrayHasKey('FR', $all);
        self::assertSame('France', $all['FR']);
    }

    public function testCountIsPositive(): void
    {
        self::assertGreaterThan(0, $this->service->count());
    }

    public function testFindCountryReturnsNullForUnknownCode(): void
    {
        self::assertNull($this->service->findCountry('ZZ'));
    }
}
