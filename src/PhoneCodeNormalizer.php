<?php

declare(strict_types=1);

namespace Iriven;

final class PhoneCodeNormalizer
{
    public function normalize(string $code): string
    {
        return ltrim(trim($code), '+');
    }
}
