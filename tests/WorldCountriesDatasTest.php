<?php

declare(strict_types=1);

namespace Iriven\Tests;

use Iriven\Factory\CountriesServiceFactory;
use Iriven\WorldCountriesDatas;
use PHPUnit\Framework\TestCase;

final class WorldCountriesDatasTest extends TestCase
{
    private WorldCountriesDatas $service;

    protected function setUp(): void
    {
        $dbFile = __DIR__ . '/../data/countries.sqlite';
        $this->service = CountriesServiceFactory::createFromSqlite($dbFile);
    }

    public function testCanResolveFranceFromAlpha2(): void
    {
        self::assertSame('France', $this->service->getCountryName('FR'));
    }

    public function testCanResolveFranceFromAlpha3(): void
    {
        self::assertSame('FRA', $this->service->getCountryAlpha3Code('FR'));
        self::assertSame('250', $this->service->getCountryNumericCode('FRA'));
    }

    public function testCanResolveFranceFromNumeric(): void
    {
        self::assertSame('FR', $this->service->getCountryAlpha2Code('250'));
    }

    public function testFluentCountryApi(): void
    {
        $country = $this->service->getCountry('FR');

        self::assertSame('France', $country->name());
        self::assertSame('.fr', $country->tld());
        self::assertTrue($country->hasCurrency('EUR'));
        self::assertTrue($country->isInRegion('Europe'));
        self::assertArrayHasKey('country', $country->all());
    }

    public function testFindCountryReturnsNullForUnknownCode(): void
    {
        self::assertNull($this->service->findCountry('ZZZ'));
    }

    public function testSearchAndFilters(): void
    {
        self::assertNotEmpty($this->service->findByName('France'));
        self::assertNotEmpty($this->service->searchCountries('fran'));
        self::assertNotEmpty($this->service->findByCurrencyCode('EUR'));
        self::assertNotEmpty($this->service->findByRegion('Europe'));
        self::assertNotEmpty($this->service->findByPhoneCode('33'));
        self::assertNotEmpty($this->service->findByTld('.fr'));
    }

    public function testGetCountryInfosReturnsAssociativeArray(): void
    {
        $infos = $this->service->getCountryInfos('FR');

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
}
