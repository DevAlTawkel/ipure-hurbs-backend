<?php

use Illuminate\Support\Number;

if (! function_exists('format_number')) {
    /**
     * Format a number for display. Uses intl when available, otherwise number_format().
     */
    function format_number(int|float $number, ?int $precision = null, ?string $locale = null): string
    {
        if (extension_loaded('intl')) {
            return Number::format($number, $precision, locale: $locale ?? app()->getLocale());
        }

        $precision ??= 0;

        return number_format($number, $precision);
    }
}
