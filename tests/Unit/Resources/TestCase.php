<?php

namespace Wsio\Tests\Ontraport\Unit\Resources;

use Mockery;
use Wsio\Ontraport\Ontraport;
use Wsio\Tests\Ontraport\TestCase as OntraportTests;

class TestCase extends OntraportTests
{
    public function setUp()
    {
        $this->ontraport = Mockery::mock(Ontraport::class);
    }

    protected function newResource($class, $objectId = null)
    {
        $this->resource = new $class($this->ontraport, $objectId);

        return $this->resource;
    }

    protected function expect($method, $uri, $data = null)
    {
        $this->ontraport->shouldReceive(strtolower($method))->with($uri, $data)->once();
    }
}
