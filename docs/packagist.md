# Packagist publication

## Préparation

```bash
composer validate
composer install
composer run build-data
composer run check-data
composer run doctor
composer run analyse
composer test
```

## Publication

1. Pousser le dépôt sur GitHub
2. Créer un tag Git :

```bash
git tag v1.0.0
git push origin v1.0.0
```

3. Soumettre le dépôt sur Packagist
4. Activer l’auto-update Packagist via GitHub hook

## Package

- Name: `irivengroup/world-datasets`
- Namespace: `Iriven\WorldDatasets\`
