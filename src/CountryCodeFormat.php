<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets;

enum CountryCodeFormat: int
{
    case ALPHA2 = 0;
    case ALPHA3 = 1;
    case NUMERIC = 2;
}
