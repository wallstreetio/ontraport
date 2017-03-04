<?php

namespace Wsio\Tests\Ontraport\Unit;

use Wsio\Ontraport\Fluent;
use Wsio\Ontraport\Response;
use Wsio\Tests\Ontraport\TestCase;

class ResponseTest extends TestCase
{
    public function testGetResponse()
    {
        $array = [
            'code' => 0
        ];

        $response = new Response($this->mockResource(), $array);

        $this->assertEquals($response->getResponse(), $array);
    }

    public function testCode()
    {
        $response = new Response($this->mockResource(), [
            'code' => 0
        ]);

        $this->assertEquals($response->code(), 0);
    }

    public function testItem()
    {
        $response = new Response($this->mockResource(), [
            'code' => 0
        ]);
        $this->assertTrue($response->item());

        $response = new Response($this->mockResource(), [
            'data' => ['id' => 1]
        ]);

        $this->assertInstanceOf(Fluent::class, $response->item());
        $this->assertEquals($response->item()->id, 1);
    }

    public function testCollection()
    {
        $response = new Response($this->mockResource(), null);
        $this->assertEquals($response->collection(), []);

        $response = new Response($this->mockResource(), [
            'data' => [
                ['id' => 1], ['id' => 2]
            ]
        ]);

        $this->assertInternalType('array', $response->collection());
        $this->assertEquals($response->collection()[0]->id, 1);
        $this->assertEquals($response->collection()[1]->id, 2);
    }
}
