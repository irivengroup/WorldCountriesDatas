<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Domain\DatasetValidator;
use Iriven\WorldDatasets\Domain\DatasetValidator;

final class DatasetValidationReport implements \JsonSerializable
{
    /**
     * @param array<int, array<string, mixed>> $duplicates
     * @param array<int, array<string, mixed>> $invalidCodes
     * @param array<int, array<string, mixed>> $warnings
     */
    public function __construct(
        private readonly array $duplicates,
        private readonly array $invalidCodes,
        private readonly array $warnings,
        private readonly bool $strict,
    ) {
    }

    /** @return array<int, array<string, mixed>> */
    public function duplicates(): array
    {
        return $this->duplicates;
    }

    /** @return array<int, array<string, mixed>> */
    public function invalidCodes(): array
    {
        return $this->invalidCodes;
    }

    /** @return array<int, array<string, mixed>> */
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

    /** @return array<string, mixed> */
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

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
