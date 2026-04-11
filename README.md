# PHP Countries Data

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XDCFPNTKUC4TU)

Service autonome de consultation des pays basé sur SQLite, avec données embarquées dans `src/data`, API moderne fluide, collections immutables, validation, exports, CLI et intégrations framework.

## Ce qui a été ajouté dans cette version

Cette version applique toutes les améliorations identifiées, à l’exception du support multilingue :

- déplacement du dossier de données de `/data` vers `src/data`
- fonctionnement autonome par défaut via `CountriesServiceFactory::make()`
- collections enrichies :
  - `values()`
  - `names()`
  - `codes()`
  - `stats()`
  - `groupByRegion()`
  - `groupByCurrency()`
  - `pluckNames()`
  - `pluckCodes()`
  - `map()`
  - `filter()`
  - `reduce()`
- filtres chaînables :
  - `inRegion()`
  - `inSubRegion()`
  - `withCurrency()`
  - `withPhoneCode()`
  - `withTld()`
  - `named()`
  - `matching()`
- tri et pagination :
  - `sortByName()`
  - `sortByCode()`
  - `sortByNumeric()`
  - `paginate()`
  - `first()`
  - `last()`
- exports :
  - `toJson()`
  - `toCsv()`
  - `exportArray()`
  - `exportJsonFile()`
  - `exportCsvFile()`
- enum `CountryCodeFormat`
- interface publique `CountriesDataInterface`
- interface `Arrayable`
- normaliseurs dédiés :
  - `CountryCodeNormalizer`
  - `PhoneCodeNormalizer`
  - `TldNormalizer`
- alias moderne `Countries`
- métadonnées via `meta()`
- statistiques via `CountriesStats`
- validation renforcée via `DatasetValidator`
- rapport de validation via `DatasetValidationReport`
- exceptions métier plus fines
- cache persistant par système de fichiers
- query builder via `CountriesQuery`
- repositories multiples :
  - `SqliteCountryRepository`
  - `ArrayCountryRepository`
  - `JsonCountryRepository`
- CLI officielle `bin/countries`
- intégrations Symfony/Laravel plus explicites

## Arborescence

```text
src/
  data/
    countries.sqlite
```

La base SQLite est désormais stockée dans `src/data/countries.sqlite`.

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

$countries = CountriesServiceFactory::make();

echo $countries->country('FR')->name();
echo $countries->country('250')->tld();
echo $countries->country('FRA')->currency()->code();

print_r($countries->country('FRA')->data());
print_r($countries->currencies()->list());
print_r($countries->countries()->alpha3()->list());
```

## Accès principal

```php
$countries->country('FR');
$countries->findCountry('FR');
```

## Formats de code

```php
use Iriven\CountryCodeFormat;

$countries->countries(CountryCodeFormat::ALPHA2)->list();
$countries->countries(CountryCodeFormat::ALPHA3)->list();
$countries->countries(CountryCodeFormat::NUMERIC)->list();
```

Raccourcis :

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
| `meta()` | `MetaInfo` | Métadonnées dataset |
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
| `__toString()` | `string` |

## `CountriesCollection`

| Méthode | Retour |
|---|---:|
| `alpha2()` | `CountriesCollection` |
| `alpha3()` | `CountriesCollection` |
| `numeric()` | `CountriesCollection` |
| `inRegion(string $name)` | `CountriesCollection` |
| `inSubRegion(string $name)` | `CountriesCollection` |
| `withCurrency(string $code)` | `CountriesCollection` |
| `withPhoneCode(string $code)` | `CountriesCollection` |
| `withTld(string $tld)` | `CountriesCollection` |
| `named(string $country)` | `CountriesCollection` |
| `matching(string $term)` | `CountriesCollection` |
| `sortByName()` | `CountriesCollection` |
| `sortByCode()` | `CountriesCollection` |
| `sortByNumeric()` | `CountriesCollection` |
| `paginate(int $offset, int $limit)` | `CountriesCollection` |
| `first()` | `?Country` |
| `last()` | `?Country` |
| `values()` | `array<Country>` |
| `names()` | `array<string,string>` |
| `codes()` | `array<int,string>` |
| `count()` | `int` |
| `stats()` | `CountriesStats` |
| `groupByRegion()` | `array` |
| `groupByCurrency()` | `array` |
| `pluckNames()` | `array` |
| `pluckCodes()` | `array` |
| `map(callable $callback)` | `array` |
| `filter(callable $callback)` | `CountriesCollection` |
| `reduce(callable $callback, mixed $initial = null)` | `mixed` |
| `list()` | `array<string,string>` |
| `exportArray()` | `array` |
| `toJson()` | `string` |
| `toCsv()` | `string` |
| `exportJsonFile(string $path)` | `void` |
| `exportCsvFile(string $path)` | `void` |
| `toStorageArray()` | `array` |
| `toApiArray()` | `array` |
| `toArray()` | `array` |
| `jsonSerialize()` | `array` |

## `CurrenciesCollection`

- `values(): array<CurrencyInfo>`
- `list(): array<string,string>`
- `countries(): array<string,array<string,string>>`
- `exportArray(): array`
- `toJson(): string`
- `toCsv(): string`
- `exportJsonFile(string $path): void`
- `exportCsvFile(string $path): void`
- `toArray(): array`
- `jsonSerialize(): array`

## `RegionsCollection`

- `values(): array<RegionInfo>`
- `list(): array<string,string>`
- `countries(): array<string,array<string,string>>`
- `exportArray(): array`
- `toJson(): string`
- `toCsv(): string`
- `exportJsonFile(string $path): void`
- `exportCsvFile(string $path): void`
- `toArray(): array`
- `jsonSerialize(): array`

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

### `CountriesStats`
- `total(): int`
- `regions(): int`
- `currencies(): int`
- `toArray(): array`
- `jsonSerialize(): array`

## Validation

### `DatasetValidator`
- `validate(array $countries, bool $strict = true): DatasetValidationReport`

### `DatasetValidationReport`
- `duplicates(): array`
- `invalidCodes(): array`
- `warnings(): array`
- `strict(): bool`
- `isValid(): bool`
- `toArray(): array`
- `jsonSerialize(): array`

## Normaliseurs

### `CountryCodeNormalizer`
- `normalize(string $code): string`
- `normalizePreservingNumeric(string $code): string`

### `PhoneCodeNormalizer`
- `normalize(string $code): string`

### `TldNormalizer`
- `normalize(string $tld): string`

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

## Agrégations et statistiques

```php
$countries->countries()->count();
$countries->countries()->stats();
$countries->countries()->groupByRegion();
$countries->countries()->groupByCurrency();
$countries->countries()->pluckNames();
$countries->countries()->pluckCodes();
```

## API fonctionnelle

```php
$countries->countries()->map(fn ($country) => $country->name());
$countries->countries()->filter(fn ($country) => $country->hasCurrency('EUR'));
$countries->countries()->reduce(fn ($carry, $country) => $carry + 1, 0);
```

## Query builder

```php
$query = new Iriven\CountriesQuery($countries->countries());

$result = $query
    ->inRegion('Europe')
    ->withCurrency('EUR')
    ->sortByName()
    ->limit(20)
    ->get();
```

## Exports

```php
$json = $countries->countries()->alpha3()->toJson();
$csv = $countries->countries()->alpha2()->toCsv();

$countries->countries()->exportJsonFile('/tmp/countries.json');
$countries->countries()->exportCsvFile('/tmp/countries.csv');

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

## Validation de dataset

### Mode strict

```php
$validator = new Iriven\DatasetValidator();
$report = $validator->validate($countries->countries()->values(), true);
```

### Mode tolérant

```php
$validator = new Iriven\DatasetValidator();
$report = $validator->validate($countries->countries()->values(), false);

$report->duplicates();
$report->invalidCodes();
$report->warnings();
$report->isValid();
```

## Repositories multiples

### SQLite

```php
$service = Iriven\CountriesServiceFactory::make();
```

### Array repository

```php
$repo = new Iriven\ArrayCountryRepository([$country1, $country2]);
$service = new Iriven\Countries($repo);
```

### JSON repository

```php
$repo = new Iriven\JsonCountryRepository('/path/to/countries.json');
$service = new Iriven\Countries($repo);
```

## Cache persistant

```php
$cache = new Iriven\FilesystemCache(__DIR__ . '/cache');
```

## CLI officielle

### Lister les pays

```bash
php bin/countries list alpha2
php bin/countries list alpha3
php bin/countries list numeric
```

### Afficher un pays

```bash
php bin/countries show FR
```

### Rechercher

```bash
php bin/countries search france
```

### Exporter

```bash
php bin/countries export countries json
php bin/countries export countries csv /tmp/countries.csv
php bin/countries export currencies json
php bin/countries export regions csv
```

### Valider

```bash
php bin/countries validate --strict
php bin/countries validate --lenient
```

# Intégration Symfony

## Service

```yaml
services:
  Iriven\Countries:
    factory: ['Iriven\CountriesServiceFactory', 'make']
```

## Usage

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

# Intégration Laravel

## Service Provider

Le provider se trouve dans :

```text
src/Bridge/Laravel/CountriesServiceProvider.php
```

## Usage

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

# Commandes utiles

```bash
composer install
php bin/import_countries.php
php bin/validate_countries.php
composer analyse
composer test
```
