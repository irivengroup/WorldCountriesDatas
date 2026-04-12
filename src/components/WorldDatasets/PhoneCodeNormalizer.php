<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\components\WorldDatasets;
use Iriven\WorldDatasets\components\WorldDatasets;


final class PhoneCodeNormalizer
{
    public function normalize(string $code): string
    {
        return ltrim(trim($code), '+');
    }
}
