<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\ValueObject\CurrencyInfo;
use Iriven\ValueObject\PhoneInfo;
use Iriven\ValueObject\RegionInfo;
use JsonSerializable;

final class Country implements JsonSerializable
{
    public function __construct(
        public readonly string $alpha2,
        public readonly string $alpha3,
        public readonly string $numeric,
        public readonly string $country,
        public readonly string $capital,
        public readonly string $tld,
        public readonly string $regionAlphaCode,
        public readonly string $regionNumCode,
        public readonly string $region,
        public readonly string $subRegionCode,
        public readonly string $subRegion,
        public readonly string $language,
        public readonly string $currencyCode,
        public readonly string $currencyName,
        public readonly string $postalCodePattern,
        public readonly string $phoneCode,
        public readonly string $intlDialingPrefix,
        public readonly string $natlDialingPrefix,
        public readonly string $subscriberPhonePattern,
    ) {
    }

    public function alpha2(): string { return $this->alpha2; }
    public function alpha3(): string { return $this->alpha3; }
    public function numeric(): string { return $this->numeric; }
    public function name(): string { return $this->country; }
    public function country(): string { return $this->country; }
    public function capital(): string { return $this->capital; }
    public function tld(): string { return $this->tld; }
    public function language(): string { return $this->language; }
    public function languages(): array
    {
        return $this->language === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $this->language)), static fn(string $v): bool => $v !== ''));
    }

    public function currencyCode(): string { return $this->currencyCode; }
    public function currencyName(): string { return $this->currencyName; }
    public function postalCodePattern(): string { return $this->postalCodePattern; }
    public function phoneCode(): string { return $this->phoneCode; }
    public function internationalDialingPrefix(): string { return $this->intlDialingPrefix; }
    public function nationalDialingPrefix(): string { return $this->natlDialingPrefix; }
    public function subscriberPhonePattern(): string { return $this->subscriberPhonePattern; }
    public function regionAlphaCode(): string { return $this->regionAlphaCode; }
    public function regionNumCode(): string { return $this->regionNumCode; }
    public function regionName(): string { return $this->region; }
    public function subRegionCode(): string { return $this->subRegionCode; }
    public function subRegionName(): string { return $this->subRegion; }

    public function currency(): CurrencyInfo
    {
        return new CurrencyInfo($this->currencyCode, $this->currencyName);
    }

    public function region(): RegionInfo
    {
        return new RegionInfo(
            $this->regionAlphaCode,
            $this->regionNumCode,
            $this->region,
            $this->subRegionCode,
            $this->subRegion,
        );
    }

    public function phone(): PhoneInfo
    {
        return new PhoneInfo(
            $this->phoneCode,
            $this->intlDialingPrefix,
            $this->natlDialingPrefix,
            $this->subscriberPhonePattern,
            $this->phoneNumberPattern(),
        );
    }

    public function phoneNumberPattern(): string
    {
        $areaCode = $this->natlDialingPrefix;
        $subscriberPattern = $this->subscriberPhonePattern;

        $pattern = $areaCode !== ''
            ? '(\\+' . preg_quote($this->phoneCode, '/') . '|' . $areaCode . ')'
            : '(\\+' . preg_quote($this->phoneCode, '/') . ')?';

        $pattern .= $subscriberPattern !== ''
            ? '(' . $subscriberPattern . ')'
            : '(\\d+)';

        return $pattern;
    }

    public function isInRegion(string $region): bool
    {
        return strcasecmp(trim($region), $this->region) === 0;
    }

    public function hasCurrency(string $code): bool
    {
        return strcasecmp(trim($code), $this->currencyCode) === 0;
    }

    public function exists(): bool
    {
        return true;
    }

    public function toIndexedArray(): array
    {
        return [
            $this->alpha2,
            $this->alpha3,
            $this->numeric,
            $this->country,
            $this->capital,
            $this->tld,
            $this->regionAlphaCode,
            $this->regionNumCode,
            $this->region,
            $this->subRegionCode,
            $this->subRegion,
            $this->language,
            $this->currencyCode,
            $this->currencyName,
            $this->postalCodePattern,
            $this->phoneCode,
            $this->intlDialingPrefix,
            $this->natlDialingPrefix,
            $this->subscriberPhonePattern,
        ];
    }

    public function toLegacyIndexedArray(): array
    {
        return $this->toIndexedArray();
    }

    public function toAssociativeArray(): array
    {
        return [
            'alpha2' => $this->alpha2,
            'alpha3' => $this->alpha3,
            'numeric' => $this->numeric,
            'country' => $this->country,
            'capital' => $this->capital,
            'tld' => $this->tld,
            'region_alpha_code' => $this->regionAlphaCode,
            'region_num_code' => $this->regionNumCode,
            'region' => $this->region,
            'sub_region_code' => $this->subRegionCode,
            'sub_region' => $this->subRegion,
            'language' => $this->language,
            'currency_code' => $this->currencyCode,
            'currency_name' => $this->currencyName,
            'postal_code_pattern' => $this->postalCodePattern,
            'phone_code' => $this->phoneCode,
            'intl_dialing_prefix' => $this->intlDialingPrefix,
            'natl_dialing_prefix' => $this->natlDialingPrefix,
            'suscriber_phone_pattern' => $this->subscriberPhonePattern,
        ];
    }

    public function toArray(): array
    {
        return $this->toAssociativeArray();
    }

    public function all(): array
    {
        return $this->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function fromDatabaseRow(array $row): self
    {
        return new self(
            alpha2: (string)($row['alpha2'] ?? ''),
            alpha3: (string)($row['alpha3'] ?? ''),
            numeric: (string)($row['numeric_code'] ?? ''),
            country: (string)($row['country_name'] ?? ''),
            capital: (string)($row['capital'] ?? ''),
            tld: (string)($row['tld'] ?? ''),
            regionAlphaCode: (string)($row['region_alpha_code'] ?? ''),
            regionNumCode: (string)($row['region_num_code'] ?? ''),
            region: (string)($row['region_name'] ?? ''),
            subRegionCode: (string)($row['sub_region_code'] ?? ''),
            subRegion: (string)($row['sub_region_name'] ?? ''),
            language: (string)($row['language'] ?? ''),
            currencyCode: (string)($row['currency_code'] ?? ''),
            currencyName: (string)($row['currency_name'] ?? ''),
            postalCodePattern: (string)($row['postal_code_pattern'] ?? ''),
            phoneCode: (string)($row['phone_code'] ?? ''),
            intlDialingPrefix: (string)($row['intl_dialing_prefix'] ?? ''),
            natlDialingPrefix: (string)($row['natl_dialing_prefix'] ?? ''),
            subscriberPhonePattern: (string)($row['subscriber_phone_pattern'] ?? ''),
        );
    }
}
