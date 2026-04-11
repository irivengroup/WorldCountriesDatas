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

    public function testCanResolveFranceFromAlpha3(): void
    {
        self::assertSame('FRA', $this->service->country('FR')->alpha3());
        self::assertSame('250', $this->service->country('FRA')->numeric());
    }

    public function testCanResolveFranceFromNumeric(): void
    {
        self::assertSame('FR', $this->service->country('250')->alpha2());
    }

    public function testGetCountryDataReturnsAssociativeArray(): void
    {
        $infos = $this->service->getCountryData('FR');

        self::assertSame('France', $infos['country']);
        self::assertSame('FRA', $infos['alpha3']);
        self::assertArrayHasKey('currency', $infos);
        self::assertArrayHasKey('region', $infos);
        self::assertArrayHasKey('phone', $infos);
    }

    public function testRegionChain(): void
    {
        $region = $this->service->country('FR')->region();

        self::assertSame('Europe', $region->name());
        self::assertSame('EU', $region->alphaCode());
    }

    public function testSubRegionChain(): void
    {
        $subRegion = $this->service->country('FR')->region()->subRegion();

        self::assertNotSame('', $subRegion->code());
        self::assertNotSame('', $subRegion->name());
    }

    public function testPhoneChain(): void
    {
        $phone = $this->service->country('FR')->phone();

        self::assertSame('33', $phone->code());
        self::assertNotSame('', $phone->pattern());
    }

    public function testFindCountryReturnsNullForUnknownCode(): void
    {
        self::assertNull($this->service->findCountry('ZZ'));
    }
}
