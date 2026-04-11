<?php

declare(strict_types=1);

namespace Iriven;

use Countable;
use Generator;
use InvalidArgumentException;
use Iriven\Contract\CountryRepositoryInterface;
use Iriven\Exception\CountryNotFoundException;
use Iriven\Exception\InvalidCountryCodeException;
use IteratorAggregate;
use Traversable;

final class WorldCountriesDatas implements Countable, IteratorAggregate
{
    public const ALPHA2 = 0;
    public const ALPHA3 = 1;
    public const NUMERIC = 2;

    public function __construct(
        private readonly CountryRepositoryInterface $repository
    ) {
    }

    public function all(): array
    {
        return array_map(
            static fn(Country $country): array => $country->toArray(),
            $this->repository->findAll()
        );
    }

    public function iterator(int|string $format = self::ALPHA2): Generator
    {
        $index = $this->normalizeFormat($format);

        foreach ($this->repository->findAll() as $country) {
            $key = match ($index) {
                self::ALPHA2 => $country->alpha2(),
                self::ALPHA3 => $country->alpha3(),
                self::NUMERIC => $country->numeric(),
            };
            yield $key => $country;
        }
    }

    public function count(): int
    {
        return $this->repository->count();
    }

    public function getIterator(): Traversable
    {
        foreach ($this->repository->findAll() as $country) {
            yield $country;
        }
    }

    public function country(string $code): Country
    {
        return $this->resolveCountry($code);
    }

    public function findCountry(string $code): ?Country
    {
        $code = trim($code);

        if ($code === '') {
            return null;
        }

        if (preg_match('/^\d{3}$/', $code) === 1) {
            return $this->repository->findOneByNumeric($code);
        }

        if (preg_match('/^[A-Za-z]{2}$/', $code) === 1) {
            return $this->repository->findOneByAlpha2($code);
        }

        if (preg_match('/^[A-Za-z]{3}$/', $code) === 1) {
            return $this->repository->findOneByAlpha3($code);
        }

        return null;
    }

    public function getCountryData(string $code): array
    {
        return $this->country($code)->toArray();
    }

    public function getAllCurrenciesCodeAndName(): array
    {
        return $this->repository->getAllCurrenciesCodeAndName();
    }

    public function getAllCountriesCodeAndName(int|string $format = self::ALPHA2): array
    {
        $index = $this->normalizeFormat($format);

        $result = [];
        foreach ($this->repository->findAll() as $country) {
            $key = match ($index) {
                self::ALPHA2 => $country->alpha2(),
                self::ALPHA3 => $country->alpha3(),
                self::NUMERIC => $country->numeric(),
            };
            $result[$key] = $country->name();
        }

        asort($result);

        return $result;
    }

    public function getAllRegionsCodeAndName(): array
    {
        return $this->repository->getAllRegionsCodeAndName();
    }

    public function getAllCountriesGroupedByRegions(): array
    {
        return $this->repository->getAllCountriesGroupedByRegions();
    }

    public function getAllCountriesGroupedByCurrencies(): array
    {
        return $this->repository->getAllCountriesGroupedByCurrencies();
    }

    public function findByName(string $name): array
    {
        $needle = mb_strtolower(trim($name));
        if ($needle === '') {
            return [];
        }

        return array_values(array_filter(
            $this->repository->findAll(),
            static fn(Country $country): bool => mb_strtolower($country->name()) === $needle
        ));
    }

    public function searchCountries(string $term): array
    {
        $needle = mb_strtolower(trim($term));
        if ($needle === '') {
            return [];
        }

        return array_values(array_filter(
            $this->repository->findAll(),
            static fn(Country $country): bool =>
                str_contains(mb_strtolower($country->name()), $needle)
                || str_contains(mb_strtolower($country->alpha2()), $needle)
                || str_contains(mb_strtolower($country->alpha3()), $needle)
                || str_contains(mb_strtolower($country->numeric()), $needle)
        ));
    }

    public function findByCurrencyCode(string $currencyCode): array
    {
        $needle = strtoupper(trim($currencyCode));
        if ($needle === '') {
            return [];
        }

        return array_values(array_filter(
            $this->repository->findAll(),
            static fn(Country $country): bool => strtoupper($country->currency()->code()) === $needle
        ));
    }

    public function findByRegion(string $region): array
    {
        $needle = mb_strtolower(trim($region));
        if ($needle === '') {
            return [];
        }

        return array_values(array_filter(
            $this->repository->findAll(),
            static fn(Country $country): bool => mb_strtolower($country->region()->name()) === $needle
        ));
    }

    public function findByPhoneCode(string $phoneCode): array
    {
        $needle = ltrim(trim($phoneCode), '+');
        if ($needle === '') {
            return [];
        }

        return array_values(array_filter(
            $this->repository->findAll(),
            static fn(Country $country): bool => ltrim($country->phone()->code(), '+') === $needle
        ));
    }

    public function findByTld(string $tld): array
    {
        $needle = mb_strtolower(trim($tld));
        if ($needle === '') {
            return [];
        }

        return array_values(array_filter(
            $this->repository->findAll(),
            static fn(Country $country): bool => mb_strtolower($country->tld()) === $needle
        ));
    }

    private function resolveCountry(string $code): Country
    {
        $code = trim($code);

        if ($code === '') {
            throw InvalidCountryCodeException::forEmptyValue();
        }

        $country = $this->findCountry($code);

        if ($country === null) {
            if (
                preg_match('/^\d{3}$/', $code) !== 1
                && preg_match('/^[A-Za-z]{2}$/', $code) !== 1
                && preg_match('/^[A-Za-z]{3}$/', $code) !== 1
            ) {
                throw InvalidCountryCodeException::forValue($code);
            }

            throw CountryNotFoundException::forKey($code);
        }

        return $country;
    }

    private function normalizeFormat(int|string $format): int
    {
        if (is_int($format) || ctype_digit((string) $format)) {
            $index = (int) $format;
            if (!in_array($index, [self::ALPHA2, self::ALPHA3, self::NUMERIC], true)) {
                throw new InvalidArgumentException(sprintf('Unsupported format: %s', (string) $format));
            }
            return $index;
        }

        return match (strtolower(trim((string) $format))) {
            'alpha-2', 'alpha2', 'iso-2', 'iso2' => self::ALPHA2,
            'alpha-3', 'alpha3', 'iso-3', 'iso3' => self::ALPHA3,
            'numeric', 'num' => self::NUMERIC,
            default => self::ALPHA2,
        };
    }
}
