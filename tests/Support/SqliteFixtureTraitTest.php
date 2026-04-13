<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Tests\Support;

use PDO;
use PHPUnit\Framework\TestCase;

final class SqliteFixtureTraitTest extends TestCase
{
    use SqliteFixtureTrait;

    public function testMakeSqliteFixturePathCreatesQueryableDatabase(): void
    {
        $path = $this->makeSqliteFixturePath();

        try {
            self::assertStringEndsWith('.sqlite', $path);
            self::assertFileExists($path);

            $pdo = new PDO('sqlite:' . $path);
            $count = (int) $pdo->query('SELECT COUNT(*) FROM countries')->fetchColumn();
            $france = $pdo->query("SELECT country_name FROM countries WHERE alpha2 = 'FR'")->fetchColumn();

            self::assertSame(2, $count);
            self::assertSame('France', $france);
        } finally {
            $this->cleanupFile($path);
        }
    }

    public function testCleanupFileDeletesFixture(): void
    {
        $path = $this->makeSqliteFixturePath();

        self::assertFileExists($path);
        $this->cleanupFile($path);
        self::assertFileDoesNotExist($path);
    }
}
