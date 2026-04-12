<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Infrastructure\Cache;
use Iriven\WorldDatasets\Application\WorldDatasets;


use DateInterval;
use DateTimeImmutable;
use InvalidArgumentException;

final class ArrayCache implements CacheInterface
{
    /** @var array<string, mixed> */
    private array $store = [];

    /** @var array<string, int|null> */
    private array $expiresAt = [];

    public function get(string $key, mixed $default = null): mixed
    {
        $this->assertKey($key);

        if (!$this->has($key)) {
            return $default;
        }

        return $this->store[$key];
    }

    public function set(string $key, mixed $value, null|int|DateInterval $ttl = null): bool
    {
        $this->assertKey($key);
        $this->store[$key] = $value;
        $this->expiresAt[$key] = $this->normalizeTtl($ttl);

        return true;
    }

    public function delete(string $key): bool
    {
        $this->assertKey($key);
        unset($this->store[$key], $this->expiresAt[$key]);

        return true;
    }

    public function clear(): bool
    {
        $this->store = [];
        $this->expiresAt = [];

        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get((string) $key, $default);
        }

        return $result;
    }

    /**
     * @param iterable<string, mixed> $values
     */
    public function setMultiple(iterable $values, null|int|DateInterval $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set((string) $key, $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete((string) $key);
        }

        return true;
    }

    public function has(string $key): bool
    {
        $this->assertKey($key);

        if (!array_key_exists($key, $this->store)) {
            return false;
        }

        $expiresAt = $this->expiresAt[$key] ?? null;
        if ($expiresAt !== null && $expiresAt < time()) {
            $this->delete($key);
            return false;
        }

        return true;
    }

    private function assertKey(string $key): void
    {
        if ($key === '') {
            throw new InvalidArgumentException('Cache key cannot be empty.');
        }
    }

    private function normalizeTtl(null|int|DateInterval $ttl): ?int
    {
        if ($ttl === null) {
            return null;
        }

        if (is_int($ttl)) {
            return time() + $ttl;
        }

        return (new DateTimeImmutable())->add($ttl)->getTimestamp();
    }
}
