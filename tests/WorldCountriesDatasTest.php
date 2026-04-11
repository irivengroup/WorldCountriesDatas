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
        self::assertSame('France', $this->service->country('FR')->name());
    }

    public function testCountryDataAlias(): void
    {
        self::assertSame(
            $this->service->country('FRA')->all(),
            $this->service->country('FRA')->data()
        );
    }

    public function testCountriesCollectionAlphaVariants(): void
    {
        self::assertArrayHasKey('FR', $this->service->countries()->alpha2()->list());
        self::assertArrayHasKey('FRA', $this->service->countries()->alpha3()->list());
        self::assertArrayHasKey('250', $this->service->countries()->numeric()->list());
    }

    public function testCurrenciesAndRegionsCollections(): void
    {
        self::assertArrayHasKey('EUR', $this->service->currencies()->list());
        self::assertNotEmpty($this->service->regions()->list());
        self::assertNotEmpty($this->service->currencies()->countries());
        self::assertNotEmpty($this->service->regions()->countries());
    }

    public function testRegionChain(): void
    {
        $region = $this->service->country('FR')->region();

        self::assertSame('Europe', $region->name());
        self::assertSame('EU', $region->alphaCode());
    }

    public function testFindCountryReturnsNullForUnknownCode(): void
    {
        self::assertNull($this->service->findCountry('ZZ'));
    }
}
