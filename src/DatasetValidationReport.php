<?php

declare(strict_types=1);

namespace Iriven;

final class DatasetValidationReport implements \JsonSerializable
{
    public function __construct(
        private readonly array $duplicates,
        private readonly array $invalidCodes,
        private readonly array $warnings,
        private readonly bool $strict,
    ) {
    }

    public function duplicates(): array
    {
        return $this->duplicates;
    }

    public function invalidCodes(): array
    {
        return $this->invalidCodes;
    }

    public function warnings(): array
    {
        return $this->warnings;
    }

    public function strict(): bool
    {
        return $this->strict;
    }

    public function isValid(): bool
    {
        return $this->duplicates === [] && $this->invalidCodes === [];
    }

    public function toArray(): array
    {
        return [
            'duplicates' => $this->duplicates,
            'invalid_codes' => $this->invalidCodes,
            'warnings' => $this->warnings,
            'strict' => $this->strict,
            'valid' => $this->isValid(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
