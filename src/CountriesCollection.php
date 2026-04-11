<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\Contract\Arrayable;

final class CountriesCollection implements Arrayable, \JsonSerializable
{
    public function __construct(
        private readonly array $countries,
        private CountryCodeFormat $format = CountryCodeFormat::ALPHA2,
    ) {
    }

    public function alpha2(): self
    {
        return new self($this->countries, CountryCodeFormat::ALPHA2);
    }

    public function alpha3(): self
    {
        return new self($this->countries, CountryCodeFormat::ALPHA3);
    }

    public function numeric(): self
    {
        return new self($this->countries, CountryCodeFormat::NUMERIC);
    }

    public function inRegion(string $name): self
    {
        $needle = mb_strtolower(trim($name));

        return new self(array_values(array_filter(
            $this->countries,
            static fn(Country $country): bool => mb_strtolower($country->region()->name()) === $needle
        )), $this->format);
    }

    public function inSubRegion(string $name): self
    {
        $needle = mb_strtolower(trim($name));

        return new self(array_values(array_filter(
            $this->countries,
            static fn(Country $country): bool => mb_strtolower($country->region()->subRegion()->name()) === $needle
        )), $this->format);
    }

    public function withCurrency(string $code): self
    {
        $needle = strtoupper(trim($code));

        return new self(array_values(array_filter(
            $this->countries,
            static fn(Country $country): bool => strtoupper($country->currency()->code()) === $needle
        )), $this->format);
    }

    public function withPhoneCode(string $code): self
    {
        $normalizer = new PhoneCodeNormalizer();
        $needle = $normalizer->normalize($code);

        return new self(array_values(array_filter(
            $this->countries,
            static fn(Country $country): bool => (new PhoneCodeNormalizer())->normalize($country->phone()->code()) === $needle
        )), $this->format);
    }

    public function withTld(string $tld): self
    {
        $normalizer = new TldNormalizer();
        $needle = $normalizer->normalize($tld);

        return new self(array_values(array_filter(
            $this->countries,
            static fn(Country $country): bool => (new TldNormalizer())->normalize($country->tld()) === $needle
        )), $this->format);
    }

    public function named(string $name): self
    {
        $needle = mb_strtolower(trim($name));

        return new self(array_values(array_filter(
            $this->countries,
            static fn(Country $country): bool => mb_strtolower($country->name()) === $needle
        )), $this->format);
    }

    public function matching(string $term): self
    {
        $needle = mb_strtolower(trim($term));

        return new self(array_values(array_filter(
            $this->countries,
            static fn(Country $country): bool =>
                str_contains(mb_strtolower($country->name()), $needle)
                || str_contains(mb_strtolower($country->alpha2()), $needle)
                || str_contains(mb_strtolower($country->alpha3()), $needle)
                || str_contains(mb_strtolower($country->numeric()), $needle)
        )), $this->format);
    }

    public function sortByName(): self
    {
        $countries = $this->countries;
        usort($countries, static fn(Country $a, Country $b): int => strcmp($a->name(), $b->name()));

        return new self($countries, $this->format);
    }

    public function sortByCode(): self
    {
        $countries = $this->countries;
        $format = $this->format;
        usort($countries, static function (Country $a, Country $b) use ($format): int {
            $left = match ($format) {
                CountryCodeFormat::ALPHA2 => $a->alpha2(),
                CountryCodeFormat::ALPHA3 => $a->alpha3(),
                CountryCodeFormat::NUMERIC => $a->numeric(),
            };
            $right = match ($format) {
                CountryCodeFormat::ALPHA2 => $b->alpha2(),
                CountryCodeFormat::ALPHA3 => $b->alpha3(),
                CountryCodeFormat::NUMERIC => $b->numeric(),
            };
            return strcmp($left, $right);
        });

        return new self($countries, $this->format);
    }

    public function sortByNumeric(): self
    {
        $countries = $this->countries;
        usort($countries, static fn(Country $a, Country $b): int => strcmp($a->numeric(), $b->numeric()));

        return new self($countries, $this->format);
    }

    public function paginate(int $offset, int $limit): self
    {
        return new self(array_slice($this->countries, max(0, $offset), max(0, $limit)), $this->format);
    }

    public function first(): ?Country
    {
        return $this->countries[0] ?? null;
    }

    public function last(): ?Country
    {
        return $this->countries === [] ? null : $this->countries[array_key_last($this->countries)];
    }

    public function values(): array
    {
        return $this->countries;
    }

    public function names(): array
    {
        return $this->list();
    }

    public function codes(): array
    {
        return array_keys($this->list());
    }

    public function list(): array
    {
        $result = [];
        foreach ($this->countries as $country) {
            $key = match ($this->format) {
                CountryCodeFormat::ALPHA2 => $country->alpha2(),
                CountryCodeFormat::ALPHA3 => $country->alpha3(),
                CountryCodeFormat::NUMERIC => $country->numeric(),
            };
            $result[$key] = $country->name();
        }

        asort($result);

        return $result;
    }

    public function exportArray(): array
    {
        return array_map(static fn(Country $country): array => $country->toArray(), $this->countries);
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
            $flat = [];
            foreach ($row as $key => $value) {
                $flat[$key] = is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value;
            }
            fputcsv($stream, $flat);
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
