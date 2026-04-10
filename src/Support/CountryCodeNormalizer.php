<?php

declare(strict_types=1);

namespace Iriven\Support;

final class CountryCodeNormalizer
{
    public function normalize(string $input): string
    {
        return strtoupper(trim($input));
    }

    public function normalizeAlpha(string $input): string
    {
        return strtoupper(trim($input));
    }

    public function normalizeNumeric(string $input): string
    {
        return trim($input);
    }

    public function normalizeTld(string $input): string
    {
        $tld = trim(strtolower($input));
        return $tld !== '' && $tld[0] !== '.' ? '.' . $tld : $tld;
    }
}
