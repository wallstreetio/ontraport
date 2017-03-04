<?php

namespace Wsio\Tests\Ontraport\Unit;

use Mockery;
use Wsio\Ontraport\Objects;
use Wsio\Ontraport\Response;
use Wsio\Ontraport\Ontraport;
use Wsio\Tests\Ontraport\TestCase;
use Wsio\Ontraport\Contracts\Client;

class OntraportTest extends TestCase
{
    protected $client;
    protected $ontraport;

    protected function setUp()
    {
        $this->client = $this->getMock(Client::class);
        $this->ontraport = new Ontraport('id', 'key', $this->client, []);
    }

    public function testGetAndSetObjects()
    {
        $this->ontraport->objects()->set([
            'contacts' => 0, 'orders' => 52,
        ]);

        $this->ontraport['roles'] = 1;
        $this->assertEquals($this->ontraport->roles, 1);

        unset($this->ontraport['roles']);
        $this->assertFalse(isset($this->ontraport['roles']));
    }

    public function testRespond()
    {
        $response = $this->ontraport->respond('resource', 'response');

        $this->assertInstanceOf(Response::class, $response);
    }

    public function testCustomObject()
    {
        $this->ontraport->objects()->set('contacts', 0);

        $this->assertEquals($this->ontraport->object(0)->getObjectId(), '0');
        $this->assertEquals($this->ontraport->object('0')->getObjectId(), '0');
        $this->assertEquals($this->ontraport->object('contacts')->getObjectId(), '0');
        $this->assertEquals($this->ontraport->contacts->getObjectId(), '0');
        $this->assertEquals($this->ontraport->contacts()->getObjectId(), '0');
        $this->assertEquals($this->ontraport['contacts']->getObjectId(), '0');
    }

    public function testClient()
    {
        $this->assertEquals($this->ontraport->client(), $this->client);
        $this->ontraport->setClient($client = $this->getMock(Client::class));
        $this->assertEquals($this->ontraport->client(), $client);
    }

    public function testExtension()
    {
        $stub = Mockery::mock(stdClass::class);

        $this->ontraport->extend('orders', function ($ontraport) use ($stub) {
            return $stub;
        });

        $stub->shouldReceive('mostRecent')->once();

        $this->ontraport->orders->mostRecent();
    }
}
