<?php

namespace App\Libraries;

use Config\RajaOngkir as RajaOngkirConfig;

/**
 * Integrasi RajaOngkir API Shipping Cost (Komerce v1).
 *
 * Endpoint:
 * - GET  /destination/domestic-destination?search=&limit=&offset=
 * - POST /calculate/domestic-cost  (origin, destination, weight, courier)
 *
 * Auth header: key = shippingKey
 *
 * @see https://rajaongkir.com/docs
 */
class RajaOngkirService
{
    protected RajaOngkirConfig $config;

    public function __construct(?RajaOngkirConfig $config = null)
    {
        $this->config = $config ?? config('RajaOngkir');
    }

    public function isConfigured(): bool
    {
        return $this->config->shippingKey !== ''
            && $this->config->baseUrl !== ''
            && $this->config->origin !== '';
    }

    /**
     * Cari destinasi domestik (kelurahan/kecamatan).
     *
     * @return list<array{id:int|string,label:string,province_name?:string,city_name?:string,district_name?:string,subdistrict_name?:string,zip_code?:string}>
     */
    public function searchDestinations(string $keyword, int $limit = 20, int $offset = 0): array
    {
        $keyword = trim($keyword);
        if ($keyword === '' || strlen($keyword) < 2) {
            return [];
        }

        $path = '/destination/domestic-destination?' . http_build_query([
            'search' => $keyword,
            'limit'  => $limit,
            'offset' => $offset,
        ]);

        $result = $this->request('GET', $path);

        return is_array($result['data'] ?? null) ? $result['data'] : [];
    }

    /**
     * Hitung ongkir domestik.
     *
     * @return list<array{name:string,code:string,service:string,description:string,cost:int,etd:string}>
     */
    public function getCost(int $destinationId, int $weightGram, ?string $courier = null): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        $courierParam = $courier ?: implode(':', $this->config->couriers);

        $result = $this->request('POST', '/calculate/domestic-cost', [
            'origin'      => $this->config->origin,
            'destination' => (string) $destinationId,
            'weight'      => max(1, $weightGram),
            'courier'     => $courierParam,
        ]);

        $data = $result['data'] ?? [];

        return is_array($data) ? $data : [];
    }

    /**
     * @param array<string, string|int> $body
     * @return array<string, mixed>
     */
    protected function request(string $method, string $path, array $body = []): array
    {
        if ($this->config->shippingKey === '') {
            return [];
        }

        $url = rtrim($this->config->baseUrl, '/') . $path;
        $ch  = curl_init();

        $headers = [
            'key: ' . $this->config->shippingKey,
            'Accept: application/json',
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);

        if (strtoupper($method) === 'POST') {
            $headers[] = 'content-type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        }

        $response = curl_exec($ch);
        $errno    = curl_errno($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno || $response === false) {
            log_message('error', 'RajaOngkir request failed: ' . $path . ' errno=' . $errno);

            return [];
        }

        $decoded = json_decode($response, true);
        if (! is_array($decoded)) {
            log_message('error', 'RajaOngkir invalid JSON: HTTP ' . $httpCode . ' ' . $path);

            return [];
        }

        $status = $decoded['meta']['status'] ?? null;
        if ($status !== null && $status !== 'success') {
            log_message('error', 'RajaOngkir API error: ' . ($decoded['meta']['message'] ?? 'unknown') . ' @ ' . $path);
        }

        return $decoded;
    }
}
