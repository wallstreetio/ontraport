<?php

namespace Wsio\Tests\Ontraport\Functional;

use Wsio\Ontraport\Ontraport;
use Wsio\Tests\Ontraport\TestCase as OntraportTests;

class TestCase extends OntraportTests
{
    protected $ontraport;

    public function setUp()
    {
        $id = getenv('ONTRAPORT_ID');
        $key = getenv('ONTRAPORT_KEY');

        if (!$id || !$key) {
            $this->markTestSkipped(
                'This test requires the ONTRAPORT_ID and ONTRAPORT_KEY' .
                ' values to be set in your phpunit.xml file.'
            );
        }

        $this->ontraport = new Ontraport($id, $key);
    }
}
