<?php

declare(strict_types=1);

namespace Iriven;

final class CurrenciesCollection
{
    public function __construct(
        private readonly array $countries,
    ) {
    }

    public function list(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $currency = $country->currency();
            if ($currency->code() === '') {
                continue;
            }
            $result[$currency->code()] = $currency->name();
        }

        asort($result);

        return $result;
    }

    public function countries(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $currency = $country->currency();
            if ($currency->code() === '') {
                continue;
            }
            $result[$currency->code()][$country->alpha2()] = $country->name();
        }

        ksort($result);

        return $result;
    }
}
