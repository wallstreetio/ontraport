<?php

namespace Wsio\Tests\Ontraport\Unit\Clients;

use ReflectionMethod;
use PHPUnit\Framework\TestCase;
use Wsio\Ontraport\Clients\CurlClient;

class CurlClientTest extends TestCase
{
    public function testDefaultHeaders()
    {
        $client = new CurlClient('id', 'key');

        $method = new ReflectionMethod($client, 'defaultHeaders');
        $method->setAccessible(true);

        $this->assertEquals($method->invoke($client), [
            'Api-Appid:id', 'Api-Key:key'
        ]);
    }
}
