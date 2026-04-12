<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Infrastructure\Persistence;

final class SqliteCountryQueryBuilder
{
    private const SELECT_COLUMNS = 'SELECT
        alpha2, alpha3, numeric_code, country_name, capital, tld,
        region_alpha_code, region_num_code, region_name,
        sub_region_code, sub_region_name, language,
        currency_code, currency_name, postal_code_pattern,
        phone_code, intl_dialing_prefix, natl_dialing_prefix,
        subscriber_phone_pattern
     FROM countries';

    public function countAll(): string
    {
        return 'SELECT COUNT(*) FROM countries';
    }

    public function selectAllOrdered(): string
    {
        return self::SELECT_COLUMNS . '
         ORDER BY country_name ASC';
    }

    public function selectAll(): string
    {
        return self::SELECT_COLUMNS;
    }

    public function findByName(): string
    {
        return self::SELECT_COLUMNS . '
         WHERE LOWER(country_name) = LOWER(:name)
         ORDER BY country_name ASC';
    }

    public function search(): string
    {
        return self::SELECT_COLUMNS . '
         WHERE LOWER(country_name) LIKE :term
            OR LOWER(alpha2) LIKE :term
            OR LOWER(alpha3) LIKE :term
            OR LOWER(numeric_code) LIKE :term
            OR LOWER(currency_code) LIKE :term
            OR LOWER(region_name) LIKE :term
            OR LOWER(tld) LIKE :term
         ORDER BY country_name ASC';
    }

    public function iterateAllLazy(): string
    {
        return self::SELECT_COLUMNS . '
         ORDER BY country_name ASC
         LIMIT :limit OFFSET :offset';
    }

    public function iterateByRegionLazy(): string
    {
        return self::SELECT_COLUMNS . '
         WHERE region_name = :region
         ORDER BY country_name ASC
         LIMIT :limit OFFSET :offset';
    }

    public function findByExactColumn(string $column): string
    {
        return self::SELECT_COLUMNS . sprintf('
         WHERE %s = :value
         ORDER BY country_name ASC', $column);
    }

    public function findOneByColumn(string $column): string
    {
        return self::SELECT_COLUMNS . sprintf('
         WHERE %s = :value
         LIMIT 1', $column);
    }

    public function currenciesMap(): string
    {
        return 'SELECT currency_code, currency_name
            FROM countries
            WHERE currency_code IS NOT NULL
              AND TRIM(currency_code) <> ""
            GROUP BY currency_code, currency_name
            ORDER BY currency_name ASC';
    }

    public function regionsMap(): string
    {
        return 'SELECT region_num_code, region_name
            FROM countries
            WHERE region_num_code IS NOT NULL
              AND TRIM(region_num_code) <> ""
            GROUP BY region_num_code, region_name
            ORDER BY region_name ASC';
    }

    public function countriesGroupedByRegions(): string
    {
        return 'SELECT region_name, alpha2, country_name
            FROM countries
            WHERE region_name IS NOT NULL
              AND TRIM(region_name) <> ""
            ORDER BY region_name ASC, country_name ASC';
    }

    public function countriesGroupedByCurrencies(): string
    {
        return 'SELECT currency_code, alpha2, country_name
            FROM countries
            WHERE currency_code IS NOT NULL
              AND TRIM(currency_code) <> ""
            ORDER BY currency_code ASC, country_name ASC';
    }
}
