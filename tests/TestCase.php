<?php

namespace Wsio\Tests\Ontraport;

use Mockery;
use Wsio\Ontraport\Resources\Resource;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    public function tearDown()
    {
        parent::tearDown();

        if ($container = Mockery::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        Mockery::close();
    }

    protected function mockResource()
    {
        return $this->getMockBuilder(Resource::class)->disableOriginalConstructor()->getMock();
    }
}
