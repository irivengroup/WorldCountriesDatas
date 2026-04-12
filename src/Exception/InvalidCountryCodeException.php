<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Exception;
use Iriven\WorldDatasets\components\WorldDatasets;


final class InvalidCountryCodeException extends CountriesDataException
{
    public static function forValue(string $value): self
    {
        return new self(sprintf(
            'Not a valid alpha2, alpha3 or numeric country key: %s',
            $value
        ));
    }

    public static function forEmptyValue(): self
    {
        return new self('Expected non-empty $IsoCode.');
    }
}
