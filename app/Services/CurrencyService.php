<?php

namespace App\Services;

class CurrencyService
{
    // Country code → currency
    const COUNTRY_MAP = [
        'US' => 'USD', 'CA' => 'CAD', 'AU' => 'AUD', 'NZ' => 'NZD',
        'GB' => 'GBP',
        'AE' => 'AED', 'SA' => 'SAR', 'QA' => 'QAR', 'KW' => 'KWD',
        'BH' => 'BHD', 'OM' => 'OMR', 'JO' => 'JOD',
        'IN' => 'INR', 'PK' => 'PKR', 'BD' => 'BDT', 'LK' => 'LKR',
        'DE' => 'EUR', 'FR' => 'EUR', 'IT' => 'EUR', 'ES' => 'EUR',
        'NL' => 'EUR', 'BE' => 'EUR', 'AT' => 'EUR', 'PT' => 'EUR',
        'GR' => 'EUR', 'IE' => 'EUR', 'FI' => 'EUR', 'SE' => 'EUR',
        'EG' => 'EGP', 'NG' => 'NGN', 'KE' => 'KES', 'ZA' => 'ZAR',
        'MY' => 'MYR', 'SG' => 'SGD', 'TH' => 'THB', 'PH' => 'PHP',
        'ID' => 'IDR', 'JP' => 'JPY', 'CN' => 'CNY', 'KR' => 'KRW',
        'TR' => 'TRY', 'BR' => 'BRL', 'MX' => 'MXN',
    ];

    // Currency code → symbol, name, USD exchange rate
    const CURRENCIES = [
        'USD' => ['symbol' => '$',    'name' => 'US Dollar',       'rate' => 1.0],
        'AED' => ['symbol' => 'AED',  'name' => 'UAE Dirham',       'rate' => 3.6725],
        'SAR' => ['symbol' => 'SAR',  'name' => 'Saudi Riyal',      'rate' => 3.75],
        'QAR' => ['symbol' => 'QAR',  'name' => 'Qatari Riyal',     'rate' => 3.64],
        'KWD' => ['symbol' => 'KWD',  'name' => 'Kuwaiti Dinar',    'rate' => 0.308],
        'BHD' => ['symbol' => 'BHD',  'name' => 'Bahraini Dinar',   'rate' => 0.376],
        'OMR' => ['symbol' => 'OMR',  'name' => 'Omani Rial',       'rate' => 0.385],
        'JOD' => ['symbol' => 'JOD',  'name' => 'Jordanian Dinar',  'rate' => 0.71],
        'GBP' => ['symbol' => '£',    'name' => 'British Pound',    'rate' => 0.79],
        'EUR' => ['symbol' => '€',    'name' => 'Euro',             'rate' => 0.92],
        'INR' => ['symbol' => '₹',    'name' => 'Indian Rupee',     'rate' => 83.5],
        'PKR' => ['symbol' => '₨',    'name' => 'Pakistani Rupee',  'rate' => 278.5],
        'CAD' => ['symbol' => 'CA$',  'name' => 'Canadian Dollar',  'rate' => 1.36],
        'AUD' => ['symbol' => 'A$',   'name' => 'Australian Dollar','rate' => 1.53],
        'SGD' => ['symbol' => 'S$',   'name' => 'Singapore Dollar', 'rate' => 1.34],
        'MYR' => ['symbol' => 'RM',   'name' => 'Malaysian Ringgit','rate' => 4.72],
        'EGP' => ['symbol' => 'EGP',  'name' => 'Egyptian Pound',   'rate' => 48.5],
        'NGN' => ['symbol' => '₦',    'name' => 'Nigerian Naira',   'rate' => 1600.0],
        'ZAR' => ['symbol' => 'R',    'name' => 'South African Rand','rate' => 18.6],
        'TRY' => ['symbol' => '₺',    'name' => 'Turkish Lira',     'rate' => 32.5],
        'JPY' => ['symbol' => '¥',    'name' => 'Japanese Yen',     'rate' => 157.0],
        'CNY' => ['symbol' => '¥',    'name' => 'Chinese Yuan',     'rate' => 7.24],
        'BRL' => ['symbol' => 'R$',   'name' => 'Brazilian Real',   'rate' => 5.05],
        'MXN' => ['symbol' => 'MX$',  'name' => 'Mexican Peso',     'rate' => 17.2],
        'THB' => ['symbol' => '฿',    'name' => 'Thai Baht',        'rate' => 35.5],
    ];

    public function forCountry(string $countryCode): array
    {
        $code = self::COUNTRY_MAP[strtoupper($countryCode)] ?? 'USD';
        return $this->forCode($code);
    }

    public function forCode(string $code): array
    {
        $code = strtoupper($code);
        $info = self::CURRENCIES[$code] ?? self::CURRENCIES['USD'];

        return [
            'code'   => $code,
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
        return $info['symbol'] . ' ' . number_format($amount, 2);
    }

    public function allCodes(): array
    {
        return array_keys(self::CURRENCIES);
    }
}
