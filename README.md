# PHP Countries Data

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XDCFPNTKUC4TU)

Service de consultation des pays basé sur SQLite, avec une API moderne centrée sur des value objects, des collections immutables chaînables, des exports et une façade stable.

## Ce qui a été ajouté

Cette version inclut les améliorations suivantes :

- collections plus expressives avec `values()`, `names()`, `codes()`
- filtres chaînables sur `CountriesCollection`
- tri et pagination
- exports `toJson()`, `toCsv()`, `exportArray()`
- enum `CountryCodeFormat`
- interfaces publiques `CountriesDataInterface` et `Arrayable`
- normaliseurs dédiés :
  - `CountryCodeNormalizer`
  - `PhoneCodeNormalizer`
  - `TldNormalizer`
- alias moderne `Countries` en plus de `WorldCountriesDatas`
- métadonnées via `meta()`
- support d’autres repositories :
  - `SqliteCountryRepository`
  - `ArrayCountryRepository`
  - `JsonCountryRepository`
- value objects homogènes avec `toArray()`, `jsonSerialize()` et `__toString()` quand pertinent

## Installation

```bash
composer install
php bin/import_countries.php
php bin/validate_countries.php
composer analyse
composer test
```

## Démarrage rapide

```php
use Iriven\CountriesServiceFactory;

require_once __DIR__ . '/vendor/autoload.php';

$countries = CountriesServiceFactory::make(__DIR__ . '/data/countries.sqlite');

echo $countries->country('FR')->name();
echo $countries->country('250')->tld();
echo $countries->country('FRA')->currency()->code();

print_r($countries->country('FRA')->data());
print_r($countries->currencies()->list());
print_r($countries->countries()->alpha3()->list());
```

# Accès principal

```php
$countries->country('FR');
$countries->findCountry('FR');
```

# Formats de code

Vous pouvez utiliser l’enum :

```php
use Iriven\CountryCodeFormat;

$countries->countries(CountryCodeFormat::ALPHA2)->list();
$countries->countries(CountryCodeFormat::ALPHA3)->list();
$countries->countries(CountryCodeFormat::NUMERIC)->list();
```

Vous pouvez aussi utiliser les raccourcis chaînables :

```php
$countries->countries()->alpha2()->list();
$countries->countries()->alpha3()->list();
$countries->countries()->numeric()->list();
```

# Inventaire détaillé des méthodes

## `WorldCountriesDatas` / `Countries`

| Méthode | Retour | Description |
|---|---:|---|
| `all()` | `array` | Tous les pays au format associatif |
| `iterator($format)` | `Generator` | Itérateur indexé |
| `count()` | `int` | Nombre total de pays |
| `getIterator()` | `Traversable` | Itération native |
| `country(string $code)` | `Country` | Accès strict |
| `findCountry(string $code)` | `?Country` | Accès tolérant |
| `countries($format = CountryCodeFormat::ALPHA2)` | `CountriesCollection` | Collection des pays |
| `currencies()` | `CurrenciesCollection` | Collection des devises |
| `regions()` | `RegionsCollection` | Collection des régions |
| `meta()` | `MetaInfo` | Métadonnées du dataset |
| `findByName(string $name)` | `array<Country>` | Recherche exacte |
| `searchCountries(string $term)` | `array<Country>` | Recherche partielle |
| `findByCurrencyCode(string $code)` | `array<Country>` | Filtre devise |
| `findByRegion(string $region)` | `array<Country>` | Filtre région |
| `findByPhoneCode(string $code)` | `array<Country>` | Filtre indicatif |
| `findByTld(string $tld)` | `array<Country>` | Filtre TLD |

## `Country`

| Méthode | Retour |
|---|---:|
| `alpha2()` | `string` |
| `alpha3()` | `string` |
| `numeric()` | `string` |
| `name()` | `string` |
| `capital()` | `string` |
| `tld()` | `string` |
| `language()` | `string` |
| `languages()` | `string` |
| `postalCodePattern()` | `string` |
| `currency()` | `CurrencyInfo` |
| `region()` | `RegionInfo` |
| `phone()` | `PhoneInfo` |
| `isInRegion(string $region)` | `bool` |
| `hasCurrency(string $code)` | `bool` |
| `exists()` | `bool` |
| `data()` | `array` |
| `toArray()` | `array` |
| `toIndexedArray()` | `array` |
| `all()` | `array` |
| `jsonSerialize()` | `array` |

## `CountriesCollection`

| Méthode | Retour | Description |
|---|---:|---|
| `alpha2()` | `CountriesCollection` | Format alpha2 |
| `alpha3()` | `CountriesCollection` | Format alpha3 |
| `numeric()` | `CountriesCollection` | Format numeric |
| `inRegion(string $name)` | `CountriesCollection` | Filtre région |
| `inSubRegion(string $name)` | `CountriesCollection` | Filtre sous-région |
| `withCurrency(string $code)` | `CountriesCollection` | Filtre devise |
| `withPhoneCode(string $code)` | `CountriesCollection` | Filtre indicatif |
| `withTld(string $tld)` | `CountriesCollection` | Filtre TLD |
| `named(string $country)` | `CountriesCollection` | Filtre nom exact |
| `matching(string $term)` | `CountriesCollection` | Recherche partielle |
| `sortByName()` | `CountriesCollection` | Tri par nom |
| `sortByCode()` | `CountriesCollection` | Tri par code courant |
| `sortByNumeric()` | `CountriesCollection` | Tri par numeric |
| `paginate(int $offset, int $limit)` | `CountriesCollection` | Pagination |
| `first()` | `?Country` | Premier élément |
| `last()` | `?Country` | Dernier élément |
| `values()` | `array<Country>` | Liste d’objets |
| `names()` | `array<string,string>` | Alias de `list()` |
| `codes()` | `array<int,string>` | Liste des codes |
| `list()` | `array<string,string>` | Code => nom |
| `exportArray()` | `array` | Export tableau |
| `toJson()` | `string` | Export JSON |
| `toCsv()` | `string` | Export CSV |
| `toArray()` | `array` | Alias export |
| `jsonSerialize()` | `array` | JSON ready |

## `CurrenciesCollection`

| Méthode | Retour |
|---|---:|
| `values()` | `array<CurrencyInfo>` |
| `list()` | `array<string,string>` |
| `countries()` | `array<string,array<string,string>>` |
| `exportArray()` | `array` |
| `toJson()` | `string` |
| `toCsv()` | `string` |
| `toArray()` | `array` |
| `jsonSerialize()` | `array` |

## `RegionsCollection`

| Méthode | Retour |
|---|---:|
| `values()` | `array<RegionInfo>` |
| `list()` | `array<string,string>` |
| `countries()` | `array<string,array<string,string>>` |
| `exportArray()` | `array` |
| `toJson()` | `string` |
| `toCsv()` | `string` |
| `toArray()` | `array` |
| `jsonSerialize()` | `array` |

## Value objects

### `CurrencyInfo`
- `code(): string`
- `name(): string`
- `toArray(): array`
- `jsonSerialize(): array`
- `__toString(): string`

### `RegionInfo`
- `alphaCode(): string`
- `numericCode(): string`
- `name(): string`
- `subRegion(): SubRegionInfo`
- `toArray(): array`
- `jsonSerialize(): array`
- `__toString(): string`

### `SubRegionInfo`
- `code(): string`
- `Code(): string`
- `name(): string`
- `Name(): string`
- `toArray(): array`
- `jsonSerialize(): array`
- `__toString(): string`

### `PhoneInfo`
- `code(): string`
- `internationalPrefix(): string`
- `nationalPrefix(): string`
- `subscriberPattern(): string`
- `pattern(): string`
- `toArray(): array`
- `jsonSerialize(): array`
- `__toString(): string`

### `MetaInfo`
- `count(): int`
- `source(): string`
- `version(): string`
- `lastUpdatedAt(): ?string`
- `toArray(): array`
- `jsonSerialize(): array`

# Utilisation détaillée

## Pays unique

```php
$country = $countries->country('FR');

$country->name();
$country->currency()->code();
$country->region()->subRegion()->name();
$country->phone()->pattern();
$country->data();
```

## Collections simples

```php
$countries->countries()->list();
$countries->countries()->alpha3()->list();
$countries->currencies()->list();
$countries->regions()->list();
```

## Filtres chaînables

```php
$countries->countries()
    ->inRegion('Europe')
    ->withCurrency('EUR')
    ->alpha2()
    ->list();

$countries->countries()
    ->inSubRegion('Western Europe')
    ->sortByName()
    ->values();

$countries->countries()
    ->withTld('.fr')
    ->matching('fr')
    ->values();

$countries->countries()
    ->withPhoneCode('+33')
    ->values();
```

## Tri, pagination, accès direct

```php
$countries->countries()
    ->sortByName()
    ->paginate(0, 25)
    ->values();

$countries->countries()->sortByCode()->first();
$countries->countries()->sortByNumeric()->last();
```

## Exports

```php
$json = $countries->countries()->alpha3()->toJson();
$csv = $countries->countries()->alpha2()->toCsv();

$currenciesJson = $countries->currencies()->toJson();
$regionsCsv = $countries->regions()->toCsv();
```

## Valeurs, codes et noms

```php
$countries->countries()->values();
$countries->countries()->names();
$countries->countries()->codes();

$countries->currencies()->values();
$countries->regions()->values();
```

## Métadonnées

```php
$countries->meta()->count();
$countries->meta()->source();
$countries->meta()->version();
$countries->meta()->lastUpdatedAt();
```

## Équivalents modernes déjà demandés

```php
$countries->country('FRA')->data();
$countries->country('FRA')->all();

$countries->currencies()->list();
$countries->currencies()->countries();

$countries->regions()->list();
$countries->regions()->countries();

$countries->countries()->alpha2()->list();
$countries->countries()->alpha3()->list();
$countries->countries()->numeric()->list();
```

# Exemple Symfony

```yaml
services:
  Iriven\Infrastructure\Cache\ArrayCache: ~

  Iriven\Support\NullLogger: ~

  Iriven\Infrastructure\Persistence\SqliteCountryRepository:
    factory: ['Iriven\Infrastructure\Persistence\SqliteCountryRepository', 'fromSqliteFile']
    arguments:
      $sqliteFilePath: '%kernel.project_dir%/data/countries.sqlite'
      $cache: '@Iriven\Infrastructure\Cache\ArrayCache'
      $logger: '@Iriven\Support\NullLogger'

  Iriven\Countries:
    factory: ['Iriven\CountriesServiceFactory', 'make']
    arguments:
      $sqliteFilePath: '%kernel.project_dir%/data/countries.sqlite'
```

```php
$payload = [
    'country' => $countries->country('FR')->toArray(),
    'european_euro_countries' => $countries->countries()
        ->inRegion('Europe')
        ->withCurrency('EUR')
        ->alpha2()
        ->list(),
    'currencies' => $countries->currencies()->list(),
];
```

# Exemple Laravel

```php
$service = app(\Iriven\Countries::class);

return response()->json([
    'country' => $service->country('FR')->toArray(),
    'alpha3_codes' => $service->countries()->alpha3()->codes(),
    'western_europe' => $service->countries()
        ->inSubRegion('Western Europe')
        ->sortByName()
        ->values(),
    'currencies' => $service->currencies()->list(),
]);
```

# Qualité et analyse

Le projet inclut aussi une base pour l’analyse statique.

```bash
composer analyse
```

# Sources de données

API pensée pour être compatible avec plusieurs sources :

- `SqliteCountryRepository`
- `ArrayCountryRepository`
- `JsonCountryRepository`

Exemple avec `ArrayCountryRepository` :

```php
$repo = new \Iriven\ArrayCountryRepository([$country1, $country2]);
$service = new \Iriven\Countries($repo);
```

# Commandes utiles

```bash
composer install
php bin/import_countries.php
php bin/validate_countries.php
composer analyse
composer test
```
