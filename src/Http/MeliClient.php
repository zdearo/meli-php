<?php

namespace zdearo\Meli\Http;

use GuzzleHttp\Client;

class MeliClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10.0,
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
