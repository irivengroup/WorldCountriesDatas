<?php

declare(strict_types=1);

namespace Iriven;

use Countable;
use Generator;
use InvalidArgumentException;
use Iriven\Contract\CountriesServiceInterface;
use Iriven\Contract\CountryRepositoryInterface;
use Iriven\Exception\CountryNotFoundException;
use Iriven\Exception\InvalidCountryCodeException;
use IteratorAggregate;
use Traversable;

final class WorldCountriesDatas implements CountriesServiceInterface, Countable, IteratorAggregate
{
    public const ALPHA2 = 0;
    public const ALPHA3 = 1;
    public const NUMERIC = 2;
    public const COUNTRY = 3;
    public const CAPITAL = 4;
    public const TLD = 5;
    public const REGION_ALPHA_CODE = 6;
    public const REGION_NUM_CODE = 7;
    public const REGION = 8;
    public const SUB_REGION_CODE = 9;
    public const SUB_REGION = 10;
    public const LANGUAGE = 11;
    public const CURRENCY_CODE = 12;
    public const CURRENCY_NAME = 13;
    public const POSTAL_CODE_PATTERN = 14;
    public const PHONE_CODE = 15;
    public const INTL_DIALING_PREFIX = 16;
    public const NATL_DIALING_PREFIX = 17;
    public const SUSCRIBER_PHONE_PATTERN = 18;

    private const HEADERS = [
        'alpha2',
        'alpha3',
        'numeric',
        'country',
        'capital',
        'tld',
        'region_alpha_code',
        'region_num_code',
        'region',
        'sub_region_code',
        'sub_region',
        'language',
        'currency_code',
        'currency_name',
        'postal_code_pattern',
        'phone_code',
        'intl_dialing_prefix',
        'natl_dialing_prefix',
        'suscriber_phone_pattern',
    ];

    public function __construct(
        private readonly CountryRepositoryInterface $repository
    ) {
    }

    public function all(): array
    {
        return array_map(
            static fn(Country $country): array => $country->toLegacyIndexedArray(),
            $this->repository->findAll()
        );
    }

    public function iterator(int|string $IsoFormat = self::ALPHA2): Generator
    {
        $index = $this->setISOFormat($IsoFormat);

        foreach ($this->repository->findAll() as $country) {
            $dataset = $country->toLegacyIndexedArray();
            yield $dataset[$index] => $dataset;
        }
    }

    public function count(): int
    {
        return $this->repository->count();
    }

    public function getIterator(): Traversable
    {
        foreach ($this->repository->findAll() as $country) {
            yield $country->toLegacyIndexedArray();
        }
    }

    public function getCountry(string $IsoCode): Country
    {
        return $this->resolveCountry($IsoCode);
    }

    public function country(string $IsoCode): Country
    {
        return $this->getCountry($IsoCode);
    }

    public function findCountry(string $IsoCode): ?Country
    {
        try {
            return $this->resolveCountry($IsoCode);
        } catch (InvalidCountryCodeException|CountryNotFoundException) {
            return null;
        }
    }

    public function getCountryInfos(string $IsoCode, bool $dataOnly = false): array
    {
        $country = $this->resolveCountry($IsoCode);

        $indexed = array_map(
            static fn(string $value): string => $value === '' ? 'n/a' : $value,
            $country->toLegacyIndexedArray()
        );

        if ($dataOnly) {
            return $indexed;
        }

        return array_combine(self::HEADERS, $indexed);
    }

    public function getCountryData(string $IsoCode, bool $dataOnly = false): array
    {
        return $this->getCountryInfos($IsoCode, $dataOnly);
    }

    public function getCountryAlpha2Code(string $IsoCode): string { return $this->getValue($IsoCode, self::ALPHA2); }
    public function getCountryAlpha3Code(string $IsoCode): string { return $this->getValue($IsoCode, self::ALPHA3); }
    public function getCountryNumericCode(string $IsoCode): string { return $this->getValue($IsoCode, self::NUMERIC); }
    public function getCountryName(string $IsoCode): string { return $this->getValue($IsoCode, self::COUNTRY); }
    public function getCountryCapitalName(string $IsoCode): string { return $this->getValue($IsoCode, self::CAPITAL); }
    public function getCountryDomain(string $IsoCode): string { return $this->getValue($IsoCode, self::TLD); }
    public function getCountryTld(string $IsoCode): string { return $this->getCountryDomain($IsoCode); }
    public function getCountryRegionAlphaCode(string $IsoCode): string { return $this->getValue($IsoCode, self::REGION_ALPHA_CODE); }
    public function getCountryRegionNumCode(string $IsoCode): string { return $this->getValue($IsoCode, self::REGION_NUM_CODE); }
    public function getCountryRegionName(string $IsoCode): string { return $this->getValue($IsoCode, self::REGION); }
    public function getCountrySubRegionCode(string $IsoCode): string { return $this->getValue($IsoCode, self::SUB_REGION_CODE); }
    public function getCountrySubRegionName(string $IsoCode): string { return $this->getValue($IsoCode, self::SUB_REGION); }
    public function getCountryLanguage(string $IsoCode): string { return $this->getValue($IsoCode, self::LANGUAGE); }
    public function getCountryLanguages(string $IsoCode): array { return $this->getCountry($IsoCode)->languages(); }
    public function getCountryCurrencyCode(string $IsoCode): string { return $this->getValue($IsoCode, self::CURRENCY_CODE); }
    public function getCountryCurrencyName(string $IsoCode): string { return $this->getValue($IsoCode, self::CURRENCY_NAME); }
    public function getCountryPostalCodePattern(string $IsoCode): string { return $this->getValue($IsoCode, self::POSTAL_CODE_PATTERN); }
    public function getCountryPhoneCode(string $IsoCode): string { return $this->getValue($IsoCode, self::PHONE_CODE); }
    public function getCountryInternationalDialingPrefix(string $IsoCode): string { return $this->getValue($IsoCode, self::INTL_DIALING_PREFIX); }
    public function getCountryNationalDialingPrefix(string $IsoCode): string { return $this->getValue($IsoCode, self::NATL_DIALING_PREFIX); }

    public function getCountryPhoneNumberPattern(string $IsoCode): string
    {
        return $this->getCountry($IsoCode)->phoneNumberPattern();
    }

    public function getAllCurrenciesCodeAndName(): array
    {
        return $this->repository->getAllCurrenciesCodeAndName();
    }

    public function getAllCountriesCodeAndName(int|string $IsoFormat = self::ALPHA2): array
    {
        $index = $this->setISOFormat($IsoFormat);

        $result = [];
        foreach ($this->repository->findAll() as $country) {
            $data = $country->toLegacyIndexedArray();
            $result[$data[$index]] = $data[self::COUNTRY];
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
        return $this->repository->findByName($name);
    }

    public function searchCountries(string $term): array
    {
        return $this->repository->search($term);
    }

    public function findByCurrencyCode(string $currencyCode): array
    {
        return $this->repository->findByCurrencyCode($currencyCode);
    }

    public function findByRegion(string $region): array
    {
        return $this->repository->findByRegion($region);
    }

    public function findByPhoneCode(string $phoneCode): array
    {
        return $this->repository->findByPhoneCode($phoneCode);
    }

    public function findByTld(string $tld): array
    {
        return $this->repository->findByTld($tld);
    }

    private function resolveCountry(string $IsoCode): Country
    {
        $isoCode = trim($IsoCode);

        if ($isoCode === '') {
            throw InvalidCountryCodeException::forEmptyValue();
        }

        if (preg_match('/^\d{3}$/', $isoCode) === 1) {
            $country = $this->repository->findOneByNumeric($isoCode);
        } elseif (preg_match('/^[A-Za-z]{2}$/', $isoCode) === 1) {
            $country = $this->repository->findOneByAlpha2($isoCode);
        } elseif (preg_match('/^[A-Za-z]{3}$/', $isoCode) === 1) {
            $country = $this->repository->findOneByAlpha3($isoCode);
        } else {
            throw InvalidCountryCodeException::forValue($isoCode);
        }

        if ($country === null) {
            throw CountryNotFoundException::forKey($isoCode);
        }

        return $country;
    }

    private function getValue(string $IsoCode, int $fieldIndex): string
    {
        $countryDatas = $this->getCountryInfos($IsoCode, true);
        return $countryDatas[$fieldIndex] ?? 'n/a';
    }

    private function setISOFormat(int|string $IsoFormat): int
    {
        if (is_int($IsoFormat) || ctype_digit((string)$IsoFormat)) {
            $index = (int)$IsoFormat;

            if ($index < self::ALPHA2 || $index > self::SUSCRIBER_PHONE_PATTERN) {
                throw new InvalidArgumentException(sprintf(
                    'Unsupported ISO format index: %s',
                    (string)$IsoFormat
                ));
            }

            return $index;
        }

        $normalized = strtolower(trim($IsoFormat));

        return match ($normalized) {
            'alpha-2', 'alpha2', 'iso-2', 'iso2' => self::ALPHA2,
            'alpha-3', 'alpha3', 'iso-3', 'iso3' => self::ALPHA3,
            'numeric', 'num' => self::NUMERIC,
            default => self::ALPHA2,
        };
    }
}
