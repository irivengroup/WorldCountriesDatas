# PHP Countries Data

Service de consultation des pays basé sur SQLite, refactoré pour un usage orienté entreprise, tout en conservant une API rétrocompatible avec la classe historique `WorldCountriesDatas` issue du dataset embarqué d'origine.

## Pré-requis

- PHP 8.2+
- extension PDO
- extension SQLite3

## Installation

```bash
composer install
php bin/import_countries.php
php bin/validate_countries.php
composer test
```

## Ce que le package propose

- façade rétrocompatible `WorldCountriesDatas`
- API fluide moderne avec `getCountry()`
- recherche par nom, région, devise, indicatif téléphonique et TLD
- repository SQLite injectable
- cache compatible PSR-16
- logger PSR-3
- factory de bootstrap
- normalisation des codes pays
- validation et sanitation des données à l'import
- tests PHPUnit
- configuration PHPStan

## Structure

- `legacy/cdata.php` : fichier historique
- `bin/import_countries.php` : import du dataset historique vers SQLite
- `bin/validate_countries.php` : contrôle qualité des données
- `src/` : domaine, repository, cache, factory, façade
- `tests/` : tests PHPUnit

## Usage simple

```php
<?php

use Iriven\Factory\CountriesServiceFactory;

require_once __DIR__ . '/vendor/autoload.php';

$countries = CountriesServiceFactory::createFromSqlite(
    __DIR__ . '/data/countries.sqlite'
);

echo $countries->getCountryName('FR') . PHP_EOL;
echo $countries->getCountryAlpha3Code('FR') . PHP_EOL;
echo $countries->getCountryNumericCode('FRA') . PHP_EOL;

print_r($countries->getCountryInfos('250'));
```

## API fluide

```php
$country = $countries->getCountry('FR');

echo $country->name();
echo $country->tld();
echo $country->currency()->code();
echo $country->region()->name();
echo $country->phone()->pattern();
print_r($country->toArray());
```

Alias disponibles :

```php
$countries->country('FR')->name();
$countries->findCountry('ZZZ'); // null
```

## Recherche métier

```php
$countries->findByName('France');
$countries->searchCountries('euro');
$countries->findByCurrencyCode('EUR');
$countries->findByRegion('Europe');
$countries->findByPhoneCode('33');
$countries->findByTld('.fr');
```

## Rétrocompatibilité conservée

Les méthodes historiques restent disponibles :

- `getCountryInfos()`
- `getCountryName()`
- `getCountryAlpha2Code()`
- `getCountryAlpha3Code()`
- `getCountryNumericCode()`
- `getAllCountriesCodeAndName()`
- `getAllRegionsCodeAndName()`

Des alias modernes ont été ajoutés :

- `getCountryData()`
- `getCountryTld()`
- `getCountryLanguages()`
- `Country::toArray()` en plus de `Country::all()`

## Qualité et validation

```bash
php bin/validate_countries.php
composer analyse
composer test
```

## Étapes d’intégration recommandées

1. copier le fichier historique dans `legacy/cdata.php`
2. lancer l’import
3. lancer la validation
4. lancer les tests
5. intégrer la nouvelle façade ou la factory dans l’application

## Exemple de bootstrap bas niveau

```php
use Iriven\Infrastructure\Cache\ArrayCache;
use Iriven\Infrastructure\Persistence\SqliteCountryRepository;
use Iriven\Support\CountryCodeNormalizer;
use Iriven\Support\NullLogger;
use Iriven\WorldCountriesDatas;

$repository = SqliteCountryRepository::fromSqliteFile(
    __DIR__ . '/data/countries.sqlite',
    new ArrayCache(),
    new NullLogger(),
    new CountryCodeNormalizer()
);

$countries = new WorldCountriesDatas($repository);
```
