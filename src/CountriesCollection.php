<?php

declare(strict_types=1);

namespace Iriven;

final class CountriesCollection
{
    public const ALPHA2 = 0;
    public const ALPHA3 = 1;
    public const NUMERIC = 2;

    public function __construct(
        private readonly array $countries,
        private int $format = self::ALPHA2,
    ) {
    }

    public function alpha2(): self
    {
        return new self($this->countries, self::ALPHA2);
    }

    public function alpha3(): self
    {
        return new self($this->countries, self::ALPHA3);
    }

    public function numeric(): self
    {
        return new self($this->countries, self::NUMERIC);
    }

    public function list(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $key = match ($this->format) {
                self::ALPHA2 => $country->alpha2(),
                self::ALPHA3 => $country->alpha3(),
                self::NUMERIC => $country->numeric(),
                default => $country->alpha2(),
            };
            $result[$key] = $country->name();
        }

        asort($result);

        return $result;
    }
}
