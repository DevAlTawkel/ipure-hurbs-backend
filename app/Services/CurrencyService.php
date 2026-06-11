<?php

namespace App\Services;

class CurrencyService
{
    // European countries that get EUR — everyone else gets USD
    const EUR_COUNTRIES = [
        'DE', 'FR', 'IT', 'ES', 'NL', 'BE', 'AT', 'PT', 'GR', 'IE',
        'FI', 'LU', 'CY', 'MT', 'SK', 'SI', 'EE', 'LV', 'LT', 'HR',
        'PL', 'CZ', 'HU', 'RO', 'BG', 'SE', 'DK', 'NO', 'CH', 'GB',
    ];

    const CURRENCIES = [
        'USD' => ['symbol' => '$',  'name' => 'US Dollar', 'rate' => 1.0],
        'EUR' => ['symbol' => '€',  'name' => 'Euro',      'rate' => 0.92],
    ];

    public function forCountry(string $countryCode): array
    {
        $code = in_array(strtoupper($countryCode), self::EUR_COUNTRIES) ? 'EUR' : 'USD';
        return $this->forCode($code);
    }

    public function forCode(string $code): array
    {
        $code = strtoupper($code);
        // Only USD and EUR supported — anything else falls back to USD
        $info = self::CURRENCIES[$code] ?? self::CURRENCIES['USD'];

        return [
            'code'   => $code === 'EUR' ? 'EUR' : 'USD',
            'symbol' => $info['symbol'],
            'name'   => $info['name'],
            'rate'   => $info['rate'],
        ];
    }

    public function convert(float $usdAmount, string $currencyCode): float
    {
        $rate = self::CURRENCIES[strtoupper($currencyCode)]['rate'] ?? 1.0;
        return round($usdAmount * $rate, 2);
    }

    public function format(float $usdAmount, string $currencyCode): string
    {
        $amount = $this->convert($usdAmount, $currencyCode);
        $info   = self::CURRENCIES[strtoupper($currencyCode)] ?? self::CURRENCIES['USD'];
        // $ before number (no space), € before number (no space)
        return $info['symbol'] . number_format($amount, 2);
    }

    public function allCodes(): array
    {
        return array_keys(self::CURRENCIES);
    }
}
