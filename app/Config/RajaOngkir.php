<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * RajaOngkir API Shipping Cost (Komerce)
 * Docs: https://rajaongkir.com/docs
 * Base: https://rajaongkir.komerce.id/api/v1
 */
class RajaOngkir extends BaseConfig
{
    public string $baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    public string $shippingKey = '';
    public string $paymentKey = '';
    public string $qrislyKey = '';
    public string $userid = '';
    public string $secretkey = '';

    /** Destination ID asal pengiriman (ID dari /destination/domestic-destination) */
    public string $origin = '';

    /** @var list<string> */
    public array $couriers = ['jne', 'jnt', 'pos'];

    public function __construct()
    {
        parent::__construct();

        $this->baseUrl      = rtrim((string) (env('rajaongkir.url', $this->baseUrl) ?? $this->baseUrl), '/');
        $this->shippingKey  = (string) (env('rajaongkir.shippingKey', '') ?? '');
        $this->paymentKey   = (string) (env('rajaongkir.paymentKey', '') ?? '');
        $this->qrislyKey    = (string) (env('rajaongkir.qrislyKey', '') ?? '');
        $this->userid       = (string) (env('rajaongkir.userid', '') ?? '');
        $this->secretkey    = (string) (env('rajaongkir.secretkey', '') ?? '');
        $this->origin       = (string) (env('rajaongkir.origin', '') ?? '');

        $couriers = env('rajaongkir.couriers', 'jne:jnt:pos') ?? 'jne:jnt:pos';
        $couriers = str_replace(',', ':', (string) $couriers);
        $this->couriers = array_values(array_filter(array_map('trim', explode(':', $couriers))));
    }
}
