<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets\Infrastructure\Persistence;
use Iriven\WorldDatasets\components\WorldDatasets;


use Iriven\WorldDatasets\components\Country;

final class SqliteCountryHydrator
{
    /**
     * @param array<string, mixed> $row
     */
    public function hydrate(array $row): Country
    {
        return Country::fromDatabaseRow($row);
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, Country>
     */
    public function hydrateMany(array $rows): array
    {
        $countries = [];

        foreach ($rows as $row) {
            $countries[] = $this->hydrate($row);
        }

        return $countries;
    }
}
