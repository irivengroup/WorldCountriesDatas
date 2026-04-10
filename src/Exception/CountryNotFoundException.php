<?php

declare(strict_types=1);

namespace Iriven\Exception;

final class CountryNotFoundException extends CountriesDataException
{
    public static function forKey(string $value): self
    {
        return new self(sprintf('No country found for key: %s', $value));
    }
}
