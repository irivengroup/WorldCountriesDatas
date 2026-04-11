<?php

declare(strict_types=1);

namespace Iriven\WorldDatasets;

use Iriven\WorldDatasets\Exception\DatasetValidationException;

final class DatasetValidator
{
    /**
     * @param list<Country> $worldDatasets
     */
    public function validate(array $worldDatasets, bool $strict = true): DatasetValidationReport
    {
        $alpha2 = [];
        $alpha3 = [];
        $numeric = [];
        $duplicates = [];
        $invalid = [];
        $warnings = [];

        foreach ($worldDatasets as $country) {
            if (!preg_match('/^[A-Z]{2}$/', $country->alpha2())) {
                $invalid[] = ['field' => 'alpha2', 'value' => $country->alpha2(), 'country' => $country->name()];
            }
            if (!preg_match('/^[A-Z]{3}$/', $country->alpha3())) {
                $invalid[] = ['field' => 'alpha3', 'value' => $country->alpha3(), 'country' => $country->name()];
            }
            if (!preg_match('/^\d{3}$/', $country->numeric())) {
                $invalid[] = ['field' => 'numeric', 'value' => $country->numeric(), 'country' => $country->name()];
            }

            $pairs = [
                ['field' => 'alpha2', 'value' => $country->alpha2()],
                ['field' => 'alpha3', 'value' => $country->alpha3()],
                ['field' => 'numeric', 'value' => $country->numeric()],
            ];

            foreach ($pairs as $pair) {
                $field = $pair['field'];
                $value = $pair['value'];
                if ($value === '') {
                    continue;
                }

                if ($field === 'alpha2') {
                    if (array_key_exists($value, $alpha2)) {
                        $duplicates[] = ['field' => $field, 'value' => $value, 'countries' => [$alpha2[$value], $country->name()]];
                    } else {
                        $alpha2[$value] = $country->name();
                    }
                    continue;
                }

                if ($field === 'alpha3') {
                    if (array_key_exists($value, $alpha3)) {
                        $duplicates[] = ['field' => $field, 'value' => $value, 'countries' => [$alpha3[$value], $country->name()]];
                    } else {
                        $alpha3[$value] = $country->name();
                    }
                    continue;
                }

                if (array_key_exists($value, $numeric)) {
                    $duplicates[] = ['field' => $field, 'value' => $value, 'countries' => [$numeric[$value], $country->name()]];
                } else {
                    $numeric[$value] = $country->name();
                }
            }

            if ($country->currency()->code() !== '' && $country->currency()->name() === '') {
                $warnings[] = ['field' => 'currency_name', 'country' => $country->name(), 'message' => 'Currency code present without currency name'];
            }
            if ($country->region()->name() !== '' && $country->region()->subRegion()->name() === '') {
                $warnings[] = ['field' => 'sub_region', 'country' => $country->name(), 'message' => 'Region present without sub-region'];
            }
            if ($country->phone()->subscriberPattern() !== '') {
                set_error_handler(static fn() => true);
                $ok = @preg_match('/' . str_replace('/', '\/', $country->phone()->subscriberPattern()) . '/', '123') !== false;
                restore_error_handler();
                if (!$ok) {
                    $warnings[] = ['field' => 'phone_pattern', 'country' => $country->name(), 'message' => 'Potentially invalid phone regex'];
                }
            }
        }

        $report = new DatasetValidationReport($duplicates, $invalid, $warnings, $strict);

        if ($strict && !$report->isValid()) {
            throw new DatasetValidationException('Dataset validation failed in strict mode.');
        }

        return $report;
    }
}
