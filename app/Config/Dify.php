<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Dify extends BaseConfig
{
    public string $chatUrl;

    public function __construct()
    {
        parent::__construct();

        $this->chatUrl = env('dify.chat_url', '');
    }
}
