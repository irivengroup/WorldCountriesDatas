<?php

declare(strict_types=1);

namespace Iriven;

final class CountryCodeNormalizer
{
    public function normalize(string $code): string
    {
        return strtoupper(trim($code));
    }

    public function normalizePreservingNumeric(string $code): string
    {
        $value = trim($code);

        return ctype_digit($value) ? $value : strtoupper($value);
    }
}
