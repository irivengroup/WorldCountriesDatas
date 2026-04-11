<?php

declare(strict_types=1);

namespace Iriven;

final class MetaInfo implements \JsonSerializable
{
    public function __construct(
        private readonly int $count,
        private readonly string $source,
        private readonly string $version,
        private readonly ?string $lastUpdatedAt = null,
    ) {
    }

    public function count(): int
    {
        return $this->count;
    }

    public function source(): string
    {
        return $this->source;
    }

    public function version(): string
    {
        return $this->version;
    }

    public function lastUpdatedAt(): ?string
    {
        return $this->lastUpdatedAt;
    }

    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'source' => $this->source,
            'version' => $this->version,
            'last_updated_at' => $this->lastUpdatedAt,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
