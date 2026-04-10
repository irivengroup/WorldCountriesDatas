<?php

declare(strict_types=1);

namespace Iriven\Tests;

use Iriven\Infrastructure\Cache\ArrayCache;
use Iriven\Infrastructure\Persistence\SqliteCountryRepository;
use Iriven\Support\CountryCodeNormalizer;
use Iriven\Support\NullLogger;
use PHPUnit\Framework\TestCase;

final class SqliteCountryRepositoryTest extends TestCase
{
    private SqliteCountryRepository $repository;

    protected function setUp(): void
    {
        $dbFile = __DIR__ . '/../data/countries.sqlite';

        $this->repository = SqliteCountryRepository::fromSqliteFile(
            $dbFile,
            new ArrayCache(),
            new NullLogger(),
            new CountryCodeNormalizer()
        );
    }

    public function testFindOneByAlpha2(): void
    {
        $country = $this->repository->findOneByAlpha2('FR');

        self::assertNotNull($country);
        self::assertSame('France', $country->country());
        self::assertSame('FRA', $country->alpha3());
    }

    public function testFindOneByAlpha3(): void
    {
        $country = $this->repository->findOneByAlpha3('FRA');

        self::assertNotNull($country);
        self::assertSame('FR', $country->alpha2());
    }

    public function testFindOneByNumeric(): void
    {
        $country = $this->repository->findOneByNumeric('250');

        self::assertNotNull($country);
        self::assertSame('France', $country->country());
    }

    public function testSearchMethods(): void
    {
        self::assertNotEmpty($this->repository->findByName('France'));
        self::assertNotEmpty($this->repository->search('euro'));
        self::assertNotEmpty($this->repository->findByCurrencyCode('EUR'));
        self::assertNotEmpty($this->repository->findByRegion('Europe'));
        self::assertNotEmpty($this->repository->findByPhoneCode('33'));
        self::assertNotEmpty($this->repository->findByTld('.fr'));
    }

    public function testCurrenciesMapIsNotEmpty(): void
    {
        $currencies = $this->repository->getAllCurrenciesCodeAndName();

        self::assertArrayHasKey('EUR', $currencies);
    }

    public function testRegionsMapIsNotEmpty(): void
    {
        $regions = $this->repository->getAllRegionsCodeAndName();

        self::assertNotEmpty($regions);
    }
}
