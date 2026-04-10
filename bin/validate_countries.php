<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$databaseFile = __DIR__ . '/../data/countries.sqlite';

if (!is_file($databaseFile)) {
    throw new RuntimeException('SQLite database not found. Run import first.');
}

$pdo = new PDO('sqlite:' . $databaseFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$errors = [];

$duplicates = $pdo->query(
    'SELECT alpha2, COUNT(*) AS total
     FROM countries
     GROUP BY alpha2
     HAVING COUNT(*) > 1'
)->fetchAll();

foreach ($duplicates as $row) {
    $errors[] = sprintf('Duplicate alpha2 detected: %s', $row['alpha2']);
}

$duplicates = $pdo->query(
    'SELECT alpha3, COUNT(*) AS total
     FROM countries
     GROUP BY alpha3
     HAVING COUNT(*) > 1'
)->fetchAll();

foreach ($duplicates as $row) {
    $errors[] = sprintf('Duplicate alpha3 detected: %s', $row['alpha3']);
}

$duplicates = $pdo->query(
    'SELECT numeric_code, COUNT(*) AS total
     FROM countries
     GROUP BY numeric_code
     HAVING COUNT(*) > 1'
)->fetchAll();

foreach ($duplicates as $row) {
    $errors[] = sprintf('Duplicate numeric code detected: %s', $row['numeric_code']);
}

$invalidAlpha2 = $pdo->query(
    "SELECT alpha2, country_name
     FROM countries
     WHERE alpha2 IS NOT NULL
       AND TRIM(alpha2) <> ''
       AND alpha2 NOT GLOB '[A-Z][A-Z]'"
)->fetchAll();

foreach ($invalidAlpha2 as $row) {
    $errors[] = sprintf('Invalid alpha2: %s (%s)', $row['alpha2'], $row['country_name']);
}

$invalidAlpha3 = $pdo->query(
    "SELECT alpha3, country_name
     FROM countries
     WHERE alpha3 IS NOT NULL
       AND TRIM(alpha3) <> ''
       AND alpha3 NOT GLOB '[A-Z][A-Z][A-Z]'"
)->fetchAll();

foreach ($invalidAlpha3 as $row) {
    $errors[] = sprintf('Invalid alpha3: %s (%s)', $row['alpha3'], $row['country_name']);
}

$invalidNumeric = $pdo->query(
    "SELECT numeric_code, country_name
     FROM countries
     WHERE numeric_code IS NOT NULL
       AND TRIM(numeric_code) <> ''
       AND LENGTH(numeric_code) != 3"
)->fetchAll();

foreach ($invalidNumeric as $row) {
    $errors[] = sprintf('Invalid numeric code: %s (%s)', $row['numeric_code'], $row['country_name']);
}

$incoherentNames = $pdo->query(
    "SELECT alpha2, alpha3, numeric_code, country_name
     FROM countries
     WHERE country_name IS NULL OR TRIM(country_name) = ''"
)->fetchAll();

foreach ($incoherentNames as $row) {
    $errors[] = sprintf(
        'Empty country name for alpha2=%s alpha3=%s numeric=%s',
        $row['alpha2'],
        $row['alpha3'],
        $row['numeric_code']
    );
}

echo 'Validation report' . PHP_EOL;
echo '=================' . PHP_EOL;

if ($errors === []) {
    echo 'No anomaly detected.' . PHP_EOL;
    exit(0);
}

foreach ($errors as $error) {
    echo '- ' . $error . PHP_EOL;
}

exit(1);
