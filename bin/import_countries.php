<?php

declare(strict_types=1);

use Iriven\WorldCountriesDatas as LegacyWorldCountriesDatas;
use PDO;

require_once __DIR__ . '/../legacy/cdata.php';

$databaseDir = __DIR__ . '/../src/data';
$databaseFile = $databaseDir . '/countries.sqlite';

if (!is_dir($databaseDir) && !mkdir($databaseDir, 0775, true) && !is_dir($databaseDir)) {
    throw new RuntimeException(sprintf('Unable to create directory: %s', $databaseDir));
}

if (is_file($databaseFile)) {
    unlink($databaseFile);
}

$pdo = new PDO('sqlite:' . $databaseFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec(
    'CREATE TABLE countries (
        alpha2 TEXT PRIMARY KEY,
        alpha3 TEXT NOT NULL UNIQUE,
        numeric_code TEXT NOT NULL UNIQUE,
        country_name TEXT NOT NULL,
        capital TEXT,
        tld TEXT,
        region_alpha_code TEXT,
        region_num_code TEXT,
        region_name TEXT,
        sub_region_code TEXT,
        sub_region_name TEXT,
        language TEXT,
        currency_code TEXT,
        currency_name TEXT,
        postal_code_pattern TEXT,
        phone_code TEXT,
        intl_dialing_prefix TEXT,
        natl_dialing_prefix TEXT,
        subscriber_phone_pattern TEXT
    )'
);

$pdo->exec('CREATE INDEX idx_countries_alpha3 ON countries(alpha3)');
$pdo->exec('CREATE INDEX idx_countries_numeric_code ON countries(numeric_code)');
$pdo->exec('CREATE INDEX idx_countries_region_name ON countries(region_name)');
$pdo->exec('CREATE INDEX idx_countries_currency_code ON countries(currency_code)');

$insert = $pdo->prepare(
    'INSERT INTO countries (
        alpha2, alpha3, numeric_code, country_name, capital, tld, region_alpha_code, region_num_code,
        region_name, sub_region_code, sub_region_name, language, currency_code, currency_name,
        postal_code_pattern, phone_code, intl_dialing_prefix, natl_dialing_prefix, subscriber_phone_pattern
    ) VALUES (
        :alpha2, :alpha3, :numeric_code, :country_name, :capital, :tld, :region_alpha_code, :region_num_code,
        :region_name, :sub_region_code, :sub_region_name, :language, :currency_code, :currency_name,
        :postal_code_pattern, :phone_code, :intl_dialing_prefix, :natl_dialing_prefix, :subscriber_phone_pattern
    )'
);

$legacy = new LegacyWorldCountriesDatas();
$rows = $legacy->all();

$seen = ['alpha2' => [], 'alpha3' => [], 'numeric' => []];
$pdo->beginTransaction();

foreach ($rows as $row) {
    $alpha2 = strtoupper(trim((string)($row[0] ?? '')));
    $alpha3 = strtoupper(trim((string)($row[1] ?? '')));
    $numeric = trim((string)($row[2] ?? ''));

    if ($alpha2 === '' || $alpha3 === '' || $numeric === '') {
        continue;
    }
    if (isset($seen['alpha2'][$alpha2]) || isset($seen['alpha3'][$alpha3]) || isset($seen['numeric'][$numeric])) {
        continue;
    }
    $seen['alpha2'][$alpha2] = true;
    $seen['alpha3'][$alpha3] = true;
    $seen['numeric'][$numeric] = true;

    $insert->execute([
        ':alpha2' => $alpha2,
        ':alpha3' => $alpha3,
        ':numeric_code' => $numeric,
        ':country_name' => trim((string)($row[3] ?? '')),
        ':capital' => trim((string)($row[4] ?? '')),
        ':tld' => trim((string)($row[5] ?? '')),
        ':region_alpha_code' => trim((string)($row[6] ?? '')),
        ':region_num_code' => trim((string)($row[7] ?? '')),
        ':region_name' => trim((string)($row[8] ?? '')),
        ':sub_region_code' => trim((string)($row[9] ?? '')),
        ':sub_region_name' => trim((string)($row[10] ?? '')),
        ':language' => trim((string)($row[11] ?? '')),
        ':currency_code' => trim((string)($row[12] ?? '')),
        ':currency_name' => trim((string)($row[13] ?? '')),
        ':postal_code_pattern' => trim((string)($row[14] ?? '')),
        ':phone_code' => trim((string)($row[15] ?? '')),
        ':intl_dialing_prefix' => trim((string)($row[16] ?? '')),
        ':natl_dialing_prefix' => trim((string)($row[17] ?? '')),
        ':subscriber_phone_pattern' => trim((string)($row[18] ?? '')),
    ]);
}

$pdo->commit();

echo 'SQLite database created: ' . $databaseFile . PHP_EOL;
