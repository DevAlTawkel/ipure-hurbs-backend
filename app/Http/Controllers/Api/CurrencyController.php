<?php

namespace App\Http\Controllers\Api;

use App\Services\CurrencyService;
use App\Services\GeoLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(
        private GeoLocationService $geo,
        private CurrencyService $currency,
    ) {}

    /**
     * GET /api/currency
     *
     * Auto-detects currency from IP address.
     * Optional: ?country=AE to override with a specific country code.
     * Optional: ?code=AED to override with a specific currency code.
     */
    public function detect(Request $request): JsonResponse
    {
        // Manual override by country or currency code
        if ($request->filled('code')) {
            $currencyData = $this->currency->forCode($request->input('code'));
            return response()->json([
                'country_code' => null,
                'country'      => null,
                'currency'     => $currencyData,
            ]);
        }

        if ($request->filled('country')) {
            $countryCode  = strtoupper($request->input('country'));
            $currencyData = $this->currency->forCountry($countryCode);
            return response()->json([
                'country_code' => $countryCode,
                'country'      => null,
                'currency'     => $currencyData,
            ]);
        }

        // Auto-detect from IP
        $ip       = $request->ip();
        $location = $this->geo->detect($ip);
        $currencyData = $this->currency->forCountry($location['country_code']);

        return response()->json([
            'ip'           => $ip,
            'country_code' => $location['country_code'],
            'country'      => $location['country'],
            'currency'     => $currencyData,
        ]);
    }

    /**
     * GET /api/currency/list
     * Returns all supported currencies.
     */
    public function list(): JsonResponse
    {
        $currencies = collect(CurrencyService::CURRENCIES)
            ->map(fn ($info, $code) => [
                'code'   => $code,
                'symbol' => $info['symbol'],
                'name'   => $info['name'],
                'rate'   => $info['rate'],
            ])
            ->values();

        return response()->json(['data' => $currencies]);
    }
}
