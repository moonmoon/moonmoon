<?php

use \PHPUnit\Framework\TestCase;
use \GuzzleHttp\Client;

class GuzzleHarness extends TestCase
{

    /** @var GuzzleHttp\Client */
    protected $client = null;

    public function setUp()
    {
        $this->client = new Client([
            'base_uri' => 'http://127.0.0.1:8081',
            'timeout' => 1,
        ]);
    }

}