<?php

declare(strict_types=1);

namespace Iriven;

use Iriven\Exception\DatasetValidationException;

final class DatasetValidator
{
    /**
     * @param list<Country> $countries
     */
    public function validate(array $countries, bool $strict = true): DatasetValidationReport
    {
        $alpha2 = [];
        $alpha3 = [];
        $numeric = [];
        $duplicates = [];
        $invalid = [];
        $warnings = [];

        foreach ($countries as $country) {
            if (!preg_match('/^[A-Z]{2}$/', $country->alpha2())) {
                $invalid[] = ['field' => 'alpha2', 'value' => $country->alpha2(), 'country' => $country->name()];
            }
            if (!preg_match('/^[A-Z]{3}$/', $country->alpha3())) {
                $invalid[] = ['field' => 'alpha3', 'value' => $country->alpha3(), 'country' => $country->name()];
            }
            if (!preg_match('/^\d{3}$/', $country->numeric())) {
                $invalid[] = ['field' => 'numeric', 'value' => $country->numeric(), 'country' => $country->name()];
            }

            foreach ([['alpha2',$country->alpha2(), &$alpha2], ['alpha3',$country->alpha3(), &$alpha3], ['numeric',$country->numeric(), &$numeric]] as [$field,$value,&$bucket]) {
                if ($value === '') {
                    continue;
                }
                if (isset($bucket[$value])) {
                    $duplicates[] = ['field' => $field, 'value' => $value, 'countries' => [$bucket[$value], $country->name()]];
                } else {
                    $bucket[$value] = $country->name();
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
