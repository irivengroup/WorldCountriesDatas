# Arborescence `src`

## Infrastructure
- `src/Infrastructure/Persistence/`
- `src/Infrastructure/Cache/`

## Components
Les composants métier sont regroupés dans `src/components/`.

### Règle
- le fichier principal d’un composant se trouve à la racine de `src/components/`
- les fichiers auxiliaires du composant sont dans un sous-dossier de même nom

## Compatibilité
Les namespaces publics existants sont conservés. L’autoload repose sur une `classmap` Composer de `src/`.
