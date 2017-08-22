<?php

namespace Wsio\Tests\Ontraport\Unit;

use Wsio\Ontraport\Objects;
use PHPUnit\Framework\TestCase;

class ObjectsTest extends TestCase
{
    protected $objects;

    public function __construct()
    {
        $this->objects = new Objects;
    }

    public function testGet()
    {
        $this->objects->set($objects = [
            'name' => 'id',
            'second_name' => 'id2'
        ]);

        $this->assertEquals($this->objects->all(), $objects);
        $this->assertEquals($this->objects->get(), $objects);

        $this->objects->set('third_name', 'id3');
        $this->assertEquals($this->objects->get('third_name'), 'id3');
        $this->assertEquals($this->objects->get('Third_Name'), 'id3');
    }

    /**
     * @expectedException \Wsio\Ontraport\Exceptions\InvalidCustomObject
     */
    public function testInvalidCustomObject()
    {
        $this->objects->find('name2');
    }
}
