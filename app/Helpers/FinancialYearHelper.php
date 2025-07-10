<?php

namespace App\Helpers;

use Carbon\Carbon;

class FinancialYearHelper
{
    public static function currentFinancialYear()
    {
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;

        if ($currentDate->month >= 4) {
            $startYear = $currentYear;
            $endYear = $currentYear + 1;
        } else {
            $startYear = $currentYear - 1;
            $endYear = $currentYear;
        }

        return $startYear . '-' . substr($endYear, -2);
    }

    public static function previousFinancialYear()
    {
        $currentYearRange = self::currentFinancialYear();

        $years = explode('-', $currentYearRange);
        $startYear = (int)$years[0] - 1;
        $endYear = (int)('20' . $years[1]) - 1;

        return $startYear . '-' . substr($endYear, -2);
    }
}
