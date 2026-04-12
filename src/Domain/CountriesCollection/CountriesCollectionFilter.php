<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\CountriesCollection;
use Iriven\WorldDatasets\Domain\CountryInfo;
use Iriven\WorldDatasets\Application\WorldDatasets;
use Iriven\WorldDatasets\Application\Support\PhoneCodeNormalizer;
use Iriven\WorldDatasets\Application\Support\TldNormalizer;


final class CountriesCollectionFilter
{
    /**
     * @param array<int, CountryInfo> $countries
     * @return array<int, CountryInfo>
     */
    public function inRegion(array $countries, string $name): array
    {
        $needle = mb_strtolower(trim($name));
        $result = [];
        foreach ($countries as $country) {
            if (mb_strtolower($country->region()->name()) === $needle) {
                $result[] = $country;
            }
        }
        return $result;
    }

    /**
     * @param array<int, CountryInfo> $countries
     * @return array<int, CountryInfo>
     */
    public function inSubRegion(array $countries, string $name): array
    {
        $needle = mb_strtolower(trim($name));
        $result = [];
        foreach ($countries as $country) {
            if (mb_strtolower($country->region()->subRegion()->name()) === $needle) {
                $result[] = $country;
            }
        }
        return $result;
    }

    /**
     * @param array<int, CountryInfo> $countries
     * @return array<int, CountryInfo>
     */
    public function withCurrency(array $countries, string $code): array
    {
        $needle = strtoupper(trim($code));
        $result = [];
        foreach ($countries as $country) {
            if (strtoupper($country->currency()->code()) === $needle) {
                $result[] = $country;
            }
        }
        return $result;
    }

    /**
     * @param array<int, CountryInfo> $countries
     * @return array<int, CountryInfo>
     */
    public function withPhoneCode(array $countries, string $code): array
    {
        $normalizer = new PhoneCodeNormalizer();
        $needle = $normalizer->normalize($code);
        $result = [];
        foreach ($countries as $country) {
            if ($normalizer->normalize($country->phone()->code()) === $needle) {
                $result[] = $country;
            }
        }
        return $result;
    }

    /**
     * @param array<int, CountryInfo> $countries
     * @return array<int, CountryInfo>
     */
    public function withTld(array $countries, string $tld): array
    {
        $normalizer = new TldNormalizer();
        $needle = $normalizer->normalize($tld);
        $result = [];
        foreach ($countries as $country) {
            if ($normalizer->normalize($country->tld()) === $needle) {
                $result[] = $country;
            }
        }
        return $result;
    }

    /**
     * @param array<int, CountryInfo> $countries
     * @return array<int, CountryInfo>
     */
    public function named(array $countries, string $name): array
    {
        $needle = mb_strtolower(trim($name));
        $result = [];
        foreach ($countries as $country) {
            if (mb_strtolower($country->name()) === $needle) {
                $result[] = $country;
            }
        }
        return $result;
    }

    /**
     * @param array<int, CountryInfo> $countries
     * @return array<int, CountryInfo>
     */
    public function matching(array $countries, string $term): array
    {
        $needle = mb_strtolower(trim($term));
        $result = [];
        foreach ($countries as $country) {
            if (
                str_contains(mb_strtolower($country->name()), $needle)
                || str_contains(mb_strtolower($country->alpha2()), $needle)
                || str_contains(mb_strtolower($country->alpha3()), $needle)
                || str_contains(mb_strtolower($country->numeric()), $needle)
            ) {
                $result[] = $country;
            }
        }
        return $result;
    }

    /**
     * @param array<int, CountryInfo> $countries
     */
    public function contains(array $countries, string $code): bool
    {
        $code = trim($code);
        foreach ($countries as $country) {
            if ($country->alpha2() === strtoupper($code) || $country->alpha3() === strtoupper($code) || $country->numeric() === $code) {
                return true;
            }
        }
        return false;
    }
}
