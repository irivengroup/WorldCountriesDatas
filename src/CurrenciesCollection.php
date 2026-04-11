<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\Contract\Arrayable;

final class CurrenciesCollection implements Arrayable, \JsonSerializable
{
    public function __construct(
        private readonly array $countries,
    ) {
    }

    public function values(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $currency = $country->currency();
            if ($currency->code() === '') {
                continue;
            }
            $result[$currency->code()] = $currency;
        }
        ksort($result);

        return array_values($result);
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

    public function exportArray(): array
    {
        return array_map(static fn(CurrencyInfo $currency): array => $currency->toArray(), $this->values());
    }

    public function toJson(int $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE): string
    {
        return (string) json_encode($this->exportArray(), $flags);
    }

    public function toCsv(): string
    {
        $rows = $this->exportArray();
        if ($rows === []) {
            return '';
        }

        $stream = fopen('php://temp', 'r+');
        fputcsv($stream, array_keys($rows[0]));
        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }
        rewind($stream);
        return (string) stream_get_contents($stream);
    }

    public function toArray(): array
    {
        return $this->exportArray();
    }

    public function jsonSerialize(): array
    {
        return $this->exportArray();
    }
}
