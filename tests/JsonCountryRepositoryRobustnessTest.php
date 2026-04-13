<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Tests;

use Iriven\WorldDatasets\Infrastructure\Persistence\JsonCountryRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class JsonCountryRepositoryRobustnessTest extends TestCase
{
    public function testConstructThrowsWhenJsonFileMissing(): void
    {
        $this->expectException(RuntimeException::class);
        new JsonCountryRepository(__DIR__ . '/missing.json');
    }

    public function testConstructThrowsWhenJsonIsInvalid(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'wd_json_invalid_');
        if ($path === false) {
            self::fail('Unable to create temp json file.');
        }

        try {
            file_put_contents($path, '{invalid-json');

            $this->expectException(RuntimeException::class);
            new JsonCountryRepository($path);
        } finally {
            if (is_file($path)) {
                self::assertTrue(unlink($path));
            }
        }
    }
}
