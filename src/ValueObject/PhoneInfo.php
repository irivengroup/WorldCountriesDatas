<?php

declare(strict_types=1);

namespace Iriven\ValueObject;

final class PhoneInfo
{
    public function __construct(
        private readonly string $countryCode,
        private readonly string $internationalPrefix,
        private readonly string $nationalPrefix,
        private readonly string $subscriberPattern,
        private readonly string $fullPattern,
    ) {
    }

    public function countryCode(): string { return $this->countryCode; }
    public function internationalPrefix(): string { return $this->internationalPrefix; }
    public function nationalPrefix(): string { return $this->nationalPrefix; }
    public function subscriberPattern(): string { return $this->subscriberPattern; }
    public function pattern(): string { return $this->fullPattern; }

    public function all(): array
    {
        return [
            'country_code' => $this->countryCode,
            'international_prefix' => $this->internationalPrefix,
            'national_prefix' => $this->nationalPrefix,
            'subscriber_pattern' => $this->subscriberPattern,
            'pattern' => $this->fullPattern,
        ];
    }
}
