<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\components\WorldDatasets;
use Iriven\WorldDatasets\components\WorldDatasets;


final class TldNormalizer
{
    public function normalize(string $tld): string
    {
        $value = trim(strtolower($tld));

        if ($value === '') {
            return '';
        }

        return str_starts_with($value, '.') ? $value : '.' . $value;
    }
}
