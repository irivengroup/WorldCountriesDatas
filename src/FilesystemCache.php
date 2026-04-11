<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets;

use Iriven\WorldDatasets\Infrastructure\Cache\CacheInterface;

final class FilesystemCache implements CacheInterface
{
    public function __construct(
        private readonly string $directory,
    ) {
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0775, true);
        }
    }

    public function has(string $key): bool
    {
        return is_file($this->path($key));
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $path = $this->path($key);
        if (!is_file($path)) {
            return $default;
        }

        $content = file_get_contents($path);
        return $content === false ? $default : unserialize($content);
    }

    public function set(string $key, mixed $value): void
    {
        file_put_contents($this->path($key), serialize($value));
    }

    public function delete(string $key): void
    {
        $path = $this->path($key);
        if (is_file($path)) {
            unlink($path);
        }
    }

    public function clear(): void
    {
        foreach (glob($this->directory . '/*.cache') ?: [] as $file) {
            @unlink($file);
        }
    }

    private function path(string $key): string
    {
        return $this->directory . '/' . sha1($key) . '.cache';
    }
}
