# PHP Countries Data

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XDCFPNTKUC4TU)

Service de consultation des pays basé sur SQLite, avec une API moderne .

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

print_r($countries->country('FRA')->data());
print_r($countries->currencies()->list());
print_r($countries->countries()->alpha3()->list());
```

## Philosophie

Cette version expose :

- `country()` pour l’accès strict à un pays
- `findCountry()` pour l’accès tolérant
- `currencies()`, `regions()` et `countries()` pour les agrégations
- value objects obligatoires pour devise, région et téléphone
- collections chaînables pour produire des listes et regroupements

## Accès principal

```php
$countries->country('FR');
$countries->findCountry('FR');
```

## Collections principales

```php
$countries->currencies()->list();
$countries->currencies()->countries();

$countries->regions()->list();
$countries->regions()->countries();

$countries->countries()->list();
$countries->countries()->alpha2()->list();
$countries->countries()->alpha3()->list();
$countries->countries()->numeric()->list();
$countries->countries(Iriven\WorldCountriesDatas::ALPHA3)->list();
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
| `countries(int|string $format = self::ALPHA2)` | `CountriesCollection` | Collection des pays avec format de code |
| `currencies()` | `CurrenciesCollection` | Collection des devises |
| `regions()` | `RegionsCollection` | Collection des régions |
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
| `data()` | `array` | Alias de `all()` |
| `toArray()` | `array` | Tableau associatif |
| `toIndexedArray()` | `array` | Tableau technique |
| `all()` | `array` | Tableau associatif |
| `jsonSerialize()` | `array` | Compatible JSON |

### `CountriesCollection`

| Méthode | Retour | Description |
|---|---:|---|
| `alpha2()` | `CountriesCollection` | Bascule le format de liste en alpha2 |
| `alpha3()` | `CountriesCollection` | Bascule le format de liste en alpha3 |
| `numeric()` | `CountriesCollection` | Bascule le format de liste en numeric |
| `list()` | `array<string,string>` | Retourne la liste code => nom |

### `CurrenciesCollection`

| Méthode | Retour | Description |
|---|---:|---|
| `list()` | `array<string,string>` | Retourne devise => nom |
| `countries()` | `array<string,array<string,string>>` | Retourne devise => [alpha2 => pays] |

### `RegionsCollection`

| Méthode | Retour | Description |
|---|---:|---|
| `list()` | `array<string,string>` | Retourne code région => nom |
| `countries()` | `array<string,array<string,string>>` | Retourne région => [alpha2 => pays] |

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
$countries->country('FR')->data();
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

### Chaînages sur les collections

```php
$countries->currencies()->list();
$countries->currencies()->countries();

$countries->regions()->list();
$countries->regions()->countries();

$countries->countries()->list();
$countries->countries()->alpha2()->list();
$countries->countries()->alpha3()->list();
$countries->countries()->numeric()->list();
$countries->countries(Iriven\WorldCountriesDatas::ALPHA3)->list();
```

## Exemples équivalents demandés

```php
$countries->country('FRA')->data();
$countries->country('FRA')->all();

$countries->currencies()->list();
$countries->countries()->list();
$countries->regions()->list();

$countries->regions()->countries();
$countries->currencies()->countries();

$countries->countries()->alpha2()->list();
$countries->countries()->alpha3()->list();
$countries->countries()->numeric()->list();
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
    'currencies_list' => $countries->currencies()->list(),
]);
```

## Exemples Laravel

```php
$service = app(\Iriven\WorldCountriesDatas::class);

$country = $service->country('FR');

return response()->json([
    'name' => $country->name(),
    'currency' => $country->currency()->toArray(),
    'region' => $country->region()->toArray(),
    'phone' => $country->phone()->toArray(),
    'countries_alpha3' => $service->countries()->alpha3()->list(),
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
