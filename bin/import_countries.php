<?php

declare(strict_types=1);

use Iriven\WorldCountriesDatas as LegacyWorldCountriesDatas;
require_once __DIR__ . '/../legacy/cdata.php';

$databaseDir = __DIR__ . '/../data';
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
$pdo->exec('CREATE INDEX idx_countries_phone_code ON countries(phone_code)');
$pdo->exec('CREATE INDEX idx_countries_tld ON countries(tld)');

$insert = $pdo->prepare(
    'INSERT INTO countries (
        alpha2,
        alpha3,
        numeric_code,
        country_name,
        capital,
        tld,
        region_alpha_code,
        region_num_code,
        region_name,
        sub_region_code,
        sub_region_name,
        language,
        currency_code,
        currency_name,
        postal_code_pattern,
        phone_code,
        intl_dialing_prefix,
        natl_dialing_prefix,
        subscriber_phone_pattern
    ) VALUES (
        :alpha2,
        :alpha3,
        :numeric_code,
        :country_name,
        :capital,
        :tld,
        :region_alpha_code,
        :region_num_code,
        :region_name,
        :sub_region_code,
        :sub_region_name,
        :language,
        :currency_code,
        :currency_name,
        :postal_code_pattern,
        :phone_code,
        :intl_dialing_prefix,
        :natl_dialing_prefix,
        :subscriber_phone_pattern
    )'
);

$legacy = new LegacyWorldCountriesDatas();
$rows = $legacy->all();

$corrections = [
    // Generic trim/casing corrections happen in sanitizeRow().
    // Put any explicit known overrides here if needed later.
];

$seen = [];
$imported = 0;
$skipped = 0;

$pdo->beginTransaction();

foreach ($rows as $row) {
    $row = sanitizeRow($row);

    $identity = implode('|', [$row[0], $row[1], $row[2]]);
    if (isset($seen[$identity])) {
        $skipped++;
        continue;
    }
    $seen[$identity] = true;

    if (isset($corrections[$row[0]])) {
        $row = array_replace($row, $corrections[$row[0]]);
    }

    $insert->execute([
        ':alpha2' => $row[0],
        ':alpha3' => $row[1],
        ':numeric_code' => $row[2],
        ':country_name' => $row[3],
        ':capital' => $row[4],
        ':tld' => $row[5],
        ':region_alpha_code' => $row[6],
        ':region_num_code' => $row[7],
        ':region_name' => $row[8],
        ':sub_region_code' => $row[9],
        ':sub_region_name' => $row[10],
        ':language' => $row[11],
        ':currency_code' => $row[12],
        ':currency_name' => $row[13],
        ':postal_code_pattern' => $row[14],
        ':phone_code' => $row[15],
        ':intl_dialing_prefix' => $row[16],
        ':natl_dialing_prefix' => $row[17],
        ':subscriber_phone_pattern' => $row[18],
    ]);

    $imported++;
}

$pdo->commit();

echo 'SQLite database created: ' . $databaseFile . PHP_EOL;
echo 'Imported rows: ' . $imported . PHP_EOL;
echo 'Skipped duplicate rows: ' . $skipped . PHP_EOL;

/**
 * @param array<int, mixed> $row
 * @return array<int, string>
 */
function sanitizeRow(array $row): array
{
    $row = array_pad($row, 19, '');
    $row = array_map(static fn(mixed $value): string => trim((string) $value), $row);

    $row[0] = strtoupper($row[0]);
    $row[1] = strtoupper($row[1]);
    $row[2] = str_pad($row[2], 3, '0', STR_PAD_LEFT);
    $row[5] = normalizeTld($row[5]);
    $row[6] = strtoupper($row[6]);
    $row[12] = strtoupper($row[12]);

    return $row;
}

function normalizeTld(string $tld): string
{
    if ($tld === '') {
        return '';
    }

    $tld = strtolower($tld);
    return $tld[0] === '.' ? $tld : '.' . $tld;
}
