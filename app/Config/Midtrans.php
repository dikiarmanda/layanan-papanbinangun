<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    public string $serverKey = '';
    public string $clientKey = '';
    public bool $isProduction = false;

    public function __construct()
    {
        parent::__construct();

        $this->serverKey     = env('midtrans.serverKey', '') ?? '';
        $this->clientKey     = env('midtrans.clientKey', '') ?? '';
        $this->isProduction  = filter_var(env('midtrans.isProduction', false), FILTER_VALIDATE_BOOLEAN);
    }
}
