<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class GeoLocationService
{
    public function detect(string $ip): array
    {
        // Private / local IPs — cannot be geolocated
        if ($this->isPrivateIp($ip)) {
            return ['country_code' => 'US', 'country' => 'United States'];
        }

        return Cache::remember("geo_ip_{$ip}", now()->addHours(24), function () use ($ip) {
            // Try ipapi.co first
            $result = $this->tryIpApiCo($ip);
            if ($result) return $result;

            // Fallback: ip-api.com (5000 req/min free, HTTP only but fine for server-side)
            $result = $this->tryIpApiCom($ip);
            if ($result) return $result;

            return ['country_code' => 'US', 'country' => 'United States'];
        });
    }

    private function tryIpApiCo(string $ip): ?array
    {
        try {
            $response = Http::timeout(3)->get("https://ipapi.co/{$ip}/json/");

            if ($response->successful()) {
                $data = $response->json();
                if (! empty($data['country_code']) && empty($data['error'])) {
                    return [
                        'country_code' => $data['country_code'],
                        'country'      => $data['country_name'] ?? $data['country_code'],
                    ];
                }
            }
        } catch (\Throwable) {}

        return null;
    }

    private function tryIpApiCom(string $ip): ?array
    {
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode");

            if ($response->successful()) {
                $data = $response->json();
                if (($data['status'] ?? '') === 'success' && ! empty($data['countryCode'])) {
                    return [
                        'country_code' => $data['countryCode'],
                        'country'      => $data['country'] ?? $data['countryCode'],
                    ];
                }
            }
        } catch (\Throwable) {}

        return null;
    }

    private function isPrivateIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1', 'localhost'])
            || filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
