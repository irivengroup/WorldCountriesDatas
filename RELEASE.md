# Release checklist

## Before release

```bash
composer validate
composer install
composer run build-data
composer run check-data
composer run doctor
composer run analyse
composer test
```

## Versioning

- Update `CHANGELOG.md`
- Update `composer.json` version if you manage it manually
- Commit
- Tag release

```bash
git tag v1.0.0
git push origin main --tags
```
