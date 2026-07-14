<?php

namespace App\Libraries;

use Config\Midtrans as MidtransConfig;
use Midtrans\Config as MidtransSdkConfig;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    protected MidtransConfig $config;

    public function __construct(?MidtransConfig $config = null)
    {
        $this->config = $config ?? config('Midtrans');
        MidtransSdkConfig::$serverKey    = $this->config->serverKey;
        MidtransSdkConfig::$isProduction = $this->config->isProduction;
        MidtransSdkConfig::$isSanitized  = true;
        MidtransSdkConfig::$is3ds        = true;
    }

    public function getClientKey(): string
    {
        return $this->config->clientKey;
    }

    /**
     * @param array{
     *   order_id: string,
     *   gross_amount: int|float,
     *   customer_details?: array{first_name?:string,email?:string,phone?:string},
     *   item_details?: list<array{id:string,price:int|float,quantity:int,name:string}>,
     *   callbacks?: array{finish?:string}
     * } $params
     */
    public function createSnapToken(array $params): string
    {
        $payload = [
            'transaction_details' => [
                'order_id'     => $params['order_id'],
                'gross_amount' => (int) round($params['gross_amount']),
            ],
        ];

        if (! empty($params['customer_details'])) {
            $payload['customer_details'] = $params['customer_details'];
        }

        if (! empty($params['item_details'])) {
            $payload['item_details'] = $params['item_details'];
        }

        if (! empty($params['callbacks'])) {
            $payload['callbacks'] = $params['callbacks'];
        }

        return Snap::getSnapToken($payload);
    }

    public function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $expected = hash(
            'sha512',
            $orderId . $statusCode . $grossAmount . $this->config->serverKey
        );

        return hash_equals($expected, $signatureKey);
    }

    /**
     * @return object|array<string, mixed>
     */
    public function getStatus(string $orderId): object|array
    {
        return Transaction::status($orderId);
    }

    public function isConfigured(): bool
    {
        return $this->config->serverKey !== '' && $this->config->clientKey !== '';
    }
}
