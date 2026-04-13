<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Tests;

use Iriven\WorldDatasets\Infrastructure\Persistence\CsvCountryRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class CsvCountryRepositoryRobustnessTest extends TestCase
{
    public function testConstructThrowsWhenHeaderIsMissing(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'wd_csv_invalid_');
        if ($path === false) {
            self::fail('Unable to create temp csv file.');
        }

        try {
            file_put_contents($path, '');

            $this->expectException(RuntimeException::class);
            new CsvCountryRepository($path);
        } finally {
            if (is_file($path)) {
                self::assertTrue(unlink($path));
            }
        }
    }
}
