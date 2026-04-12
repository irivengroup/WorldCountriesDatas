<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Domain;
use Iriven\WorldDatasets\Application\WorldDatasets;


use Iriven\WorldDatasets\Contract\Arrayable;

final class PhoneInfo implements Arrayable, \JsonSerializable
{
    public function __construct(
        private readonly string $code,
        private readonly string $internationalPrefix,
        private readonly string $nationalPrefix,
        private readonly string $subscriberPattern,
        private readonly string $pattern,
    ) {
    }

    public function code(): string
    {
        return $this->code;
    }

    public function internationalPrefix(): string
    {
        return $this->internationalPrefix;
    }

    public function nationalPrefix(): string
    {
        return $this->nationalPrefix;
    }

    public function subscriberPattern(): string
    {
        return $this->subscriberPattern;
    }

    public function pattern(): string
    {
        return $this->pattern;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'international_prefix' => $this->internationalPrefix,
            'national_prefix' => $this->nationalPrefix,
            'subscriber_pattern' => $this->subscriberPattern,
            'pattern' => $this->pattern,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
