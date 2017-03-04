<?php

namespace Wsio\Tests\Ontraport\Unit;

use Mockery;
use Wsio\Ontraport\Fluent;
use Wsio\Tests\Ontraport\TestCase;
use Wsio\Ontraport\Resources\Resource;

class FluentTest extends TestCase
{
    public function testAttributesAreSetByConstructor()
    {
        $fluent = new Fluent($this->mockResource(), [
            'firstname' => 'Tamer'
        ]);

        $this->assertEquals($fluent->firstname, 'Tamer');
    }

    public function testGetAndSetAttributes()
    {
        $fluent = new Fluent($this->mockResource());
        $fluent->firstname = 'Tamer';
        $this->assertEquals($fluent->firstname, 'Tamer');
        $fluent['lastname'] = 'Ashkar';
        $this->assertEquals($fluent['lastname'], 'Ashkar');
    }

    public function testGet()
    {
        $array = [
            'firstname' => 'Tamer'
        ];

        $fluent = new Fluent($this->mockResource(), $array);

        $this->assertEquals($fluent->get(), $array);
        $this->assertEquals($fluent->get('firstname'), 'Tamer');
        $this->assertEquals($fluent->get('lastname', 'jackass'), 'jackass');
    }

    public function testToArrayReturnsAttributes()
    {
        $array = ['firstname' => 'Tamer', 'age' => 25];
        $fluent = new Fluent($this->mockResource(), $array);
        $this->assertEquals($array, $fluent->toArray());
    }

    public function testToJsonEncodesTheToArrayResult()
    {
        $fluent = $this->getMockBuilder(Fluent::class)
            ->disableOriginalConstructor()
            ->setMethods(['toArray'])
            ->getMock();

        $fluent->expects($this->once())->method('toArray')->will($this->returnValue('foo'));
        $results = $fluent->toJson();
        $this->assertJsonStringEqualsJsonString(json_encode('foo'), $results);
    }

    public function testIssetMagicMethod()
    {
        $array = ['firstname' => 'Tamer', 'age' => 25];
        $fluent = new Fluent($this->mockResource(), $array);
        $this->assertTrue(isset($fluent['firstname']));
        unset($fluent->firstname);
        unset($fluent['age']);
        $this->assertFalse(isset($fluent->firstname));
        $this->assertFalse(isset($fluent['age']));
    }

    public function testSave()
    {
        $fluent = new Fluent($resource = Mockery::mock(Resource::class), [
            'firstname' => 'Tamer'
        ]);

        $resource->shouldReceive('update')->once()->with([
           'firstname' => 'changed', 'lastname' => 'me'
        ]);

        $fluent->firstname = 'changed';
        $fluent->lastname = 'me';
        $fluent->save();
    }

    public function testDelete()
    {
        $fluent = new Fluent($resource = Mockery::mock(Resource::class), [
            'id' => 1
        ]);

        $resource->shouldReceive('delete')->once();

        $fluent->delete();
    }
}
