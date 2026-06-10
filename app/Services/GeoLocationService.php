<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoLocationService
{
    public function detect(string $ip): array
    {
        // Localhost fallback
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost'])) {
            return ['country_code' => 'US', 'country' => 'United States'];
        }

        return Cache::remember("geo_ip_{$ip}", now()->addHours(24), function () use ($ip) {
            try {
                $response = Http::timeout(4)->get("https://ipapi.co/{$ip}/json/");

                if ($response->successful()) {
                    $data = $response->json();

                    if (! empty($data['country_code']) && empty($data['error'])) {
                        return [
                            'country_code' => $data['country_code'],
                            'country'      => $data['country_name'] ?? $data['country_code'],
                        ];
                    }
                }
            } catch (\Throwable) {
                // silently fall through to default
            }

            return ['country_code' => 'US', 'country' => 'United States'];
        });
    }
}
