# Changelog

## 1.0.0
- Rationalisation de l’architecture
- Source de vérité déplacée dans `src/data`
- Source SQLite par défaut
- Renommage `WorldDatasets` -> `WorldDatasets`
- Query builder, exports, validation, doctor, pipeline data
- Métadonnées de dataset, checksums, CI, documentation étendue

# Convention retenue

Cette version conserve volontairement :

- `WorldDatasetsService` comme service principal
- `WorldDatasetsFactory` comme factory principale
- `countries()` comme point d’entrée de collection
- `CountriesCollection` comme nom officiel de la collection de pays
- la variable d’exemple `$worldDatasets` dans toute la documentation

Autrement dit :
- on **ne migre pas** `CountriesCollection` vers `DatasetsCollection`
- on garde `countries()` → `CountriesCollection`

---

# Version publique stabilisée v1

Cette archive ne contient plus d’alias de transition.  
L’API publique officielle repose uniquement sur :

- `Iriven\WorldDatasets\Application\WorldDatasetsService`
- `Iriven\WorldDatasets\Application\Factory\WorldDatasetsFactory`
- `Iriven\WorldDatasets\Application\Config\WorldDatasetsRuntimeConfig`
- `Iriven\WorldDatasets\Domain\CountriesCollection`
- `Iriven\WorldDatasets\Domain\CurrencyCollection`
- `Iriven\WorldDatasets\Domain\RegionCollection`
- `Iriven\WorldDatasets\Application\Query\WorldDatasetsQuery`
- `Iriven\WorldDatasets\Application\Stats\WorldDatasetsStats`

---

# Public API harmonisée

Le package est maintenant aligné de bout en bout :

- package Composer : `irivengroup/world-datasets`
- namespace principal : `Iriven\WorldDatasets\`
- service principal : `WorldDatasetsService`
- factory principale : `WorldDatasetsFactory`
- collection principale : `CountriesCollection`
- query builder : `WorldDatasetsQuery`

---

# Installation

```bash
composer require irivengroup/world-datasets
```

Namespace principal :

```php
use Iriven\WorldDatasets\Application\WorldDatasetsService;
use Iriven\WorldDatasets\Application\Factory\WorldDatasetsFactory;
```

---
