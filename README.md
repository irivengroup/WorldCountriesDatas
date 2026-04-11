# PHP Countries Data

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XDCFPNTKUC4TU)

Service de consultation des pays basé sur SQLite, avec une API moderne centrée sur `country()` et le chaînage fluide.

## Pré-requis

- PHP 8.2+
- extension PDO
- extension SQLite3

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

print_r($countries->country('FRA')->all());
```

## Accès principal

```php
$countries->country('FR');
$countries->findCountry('FR');
```

## Inventaire des méthodes disponibles

### `WorldCountriesDatas`

| Méthode | Retour | Description |
|---|---:|---|
| `all()` | `array` | Tous les pays au format associatif |
| `iterator($format = self::ALPHA2)` | `Generator` | Itérateur indexé par alpha2, alpha3 ou numeric |
| `count()` | `int` | Nombre total de pays |
| `getIterator()` | `Traversable` | Support `foreach` |
| `country(string $code)` | `Country` | Retourne un pays ou lève une exception |
| `findCountry(string $code)` | `?Country` | Retourne `null` si introuvable |
| `getCountryData(string $code)` | `array` | Données du pays au format associatif |
| `getAllCurrenciesCodeAndName()` | `array<string,string>` | Devise => nom |
| `getAllCountriesCodeAndName($format = self::ALPHA2)` | `array<string,string>` | Code => nom pays |
| `getAllRegionsCodeAndName()` | `array<string,string>` | Région => nom |
| `getAllCountriesGroupedByRegions()` | `array<string,array<string,string>>` | Pays groupés par région |
| `getAllCountriesGroupedByCurrencies()` | `array<string,array<string,string>>` | Pays groupés par devise |
| `findByName(string $name)` | `array<Country>` | Recherche exacte par nom |
| `searchCountries(string $term)` | `array<Country>` | Recherche partielle |
| `findByCurrencyCode(string $code)` | `array<Country>` | Recherche par devise |
| `findByRegion(string $region)` | `array<Country>` | Recherche par région |
| `findByPhoneCode(string $code)` | `array<Country>` | Recherche par indicatif |
| `findByTld(string $tld)` | `array<Country>` | Recherche par TLD |

### `Country`

| Méthode | Retour | Description |
|---|---:|---|
| `alpha2()` | `string` | Code alpha2 |
| `alpha3()` | `string` | Code alpha3 |
| `numeric()` | `string` | Code numérique |
| `name()` | `string` | Nom du pays |
| `capital()` | `string` | Capitale |
| `tld()` | `string` | Domaine de premier niveau |
| `language()` | `string` | Langues |
| `languages()` | `string` | Alias |
| `postalCodePattern()` | `string` | Regex code postal |
| `currency()` | `CurrencyInfo` | Objet devise |
| `region()` | `RegionInfo` | Objet région |
| `phone()` | `PhoneInfo` | Objet téléphone |
| `isInRegion(string $region)` | `bool` | Vérifie l’appartenance régionale |
| `hasCurrency(string $code)` | `bool` | Vérifie la devise |
| `exists()` | `bool` | Toujours `true` si obtenu via `country()` |
| `toArray()` | `array` | Tableau associatif |
| `toIndexedArray()` | `array` | Tableau technique |
| `all()` | `array` | Alias de `toArray()` |
| `jsonSerialize()` | `array` | Compatible JSON |

### `CurrencyInfo`

- `code(): string`
- `name(): string`
- `toArray(): array`

### `RegionInfo`

- `alphaCode(): string`
- `numericCode(): string`
- `name(): string`
- `subRegion(): SubRegionInfo`
- `toArray(): array`

### `SubRegionInfo`

- `code(): string`
- `Code(): string`
- `name(): string`
- `Name(): string`
- `toArray(): array`

### `PhoneInfo`

- `code(): string`
- `internationalPrefix(): string`
- `nationalPrefix(): string`
- `subscriberPattern(): string`
- `pattern(): string`
- `toArray(): array`

## Totalité des chaînages possibles

### Chaînages directs sur `Country`

```php
$countries->country('FR')->alpha2();
$countries->country('FR')->alpha3();
$countries->country('FR')->numeric();
$countries->country('FR')->name();
$countries->country('FR')->capital();
$countries->country('FR')->tld();
$countries->country('FR')->language();
$countries->country('FR')->languages();
$countries->country('FR')->postalCodePattern();
$countries->country('FR')->exists();
$countries->country('FR')->isInRegion('Europe');
$countries->country('FR')->hasCurrency('EUR');
$countries->country('FR')->toArray();
$countries->country('FR')->toIndexedArray();
$countries->country('FR')->all();
$countries->country('FR')->jsonSerialize();
```

### Chaînages via `currency()`

```php
$countries->country('FR')->currency()->code();
$countries->country('FR')->currency()->name();
$countries->country('FR')->currency()->toArray();
```

### Chaînages via `region()`

```php
$countries->country('FR')->region()->alphaCode();
$countries->country('FR')->region()->numericCode();
$countries->country('FR')->region()->name();
$countries->country('FR')->region()->subRegion()->code();
$countries->country('FR')->region()->subRegion()->Code();
$countries->country('FR')->region()->subRegion()->name();
$countries->country('FR')->region()->subRegion()->Name();
$countries->country('FR')->region()->toArray();
```

### Chaînages via `phone()`

```php
$countries->country('FR')->phone()->code();
$countries->country('FR')->phone()->internationalPrefix();
$countries->country('FR')->phone()->nationalPrefix();
$countries->country('FR')->phone()->subscriberPattern();
$countries->country('FR')->phone()->pattern();
$countries->country('FR')->phone()->toArray();
```

## Exemples Symfony

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

  Iriven\WorldCountriesDatas:
    arguments:
      $repository: '@Iriven\Infrastructure\Persistence\SqliteCountryRepository'
```

```php
$country = $countries->country($code);

return $this->json([
    'name' => $country->name(),
    'currency' => $country->currency()->toArray(),
    'region' => $country->region()->toArray(),
    'phone' => $country->phone()->toArray(),
]);
```

## Exemples Laravel

```php
$country = app(\Iriven\WorldCountriesDatas::class)
    ->country('FR');

return response()->json([
    'name' => $country->name(),
    'currency' => $country->currency()->toArray(),
    'region' => $country->region()->toArray(),
    'phone' => $country->phone()->toArray(),
]);
```

## Commandes utiles

```bash
composer install
php bin/import_countries.php
php bin/validate_countries.php
composer analyse
composer test
```
