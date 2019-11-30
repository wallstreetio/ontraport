<?php

namespace Wsio\Tests\Ontraport\Unit\Resources;

use Wsio\Ontraport\Resources\OntraportObject;

class OntraportObjectTest extends TestCase
{
    public function testFind()
    {
        $this->expect('GET', 'object', [
            'objectID' => 0,
            'id' => 1
        ]);

        $this->ontraport->shouldReceive('item')->once();

        $this->resource()->find(1);
    }

    public function testFirst()
    {
        $this->expect('GET', 'objects', [
            'objectID' => 0,
            'range' => 1
        ]);

        $this->ontraport->shouldReceive('collection')->once();

        $this->assertNull($this->resource()->first());
    }

    public function testGet()
    {
        $this->expect('GET', 'objects', [
            'objectID' => 0
        ]);

        $this->ontraport->shouldReceive('collection')->once();

        $this->resource()->get();
    }

    public function testCreate()
    {
        $this->expect('POST', 'objects', [
            'objectID' => 0,
            'firstname' => 'tamer',
            'lastname' => 'ashkar'
        ]);

        $this->ontraport->shouldReceive('item')->once();

        $this->resource()->create([
            'firstname' => 'tamer',
            'lastname' => 'ashkar'
        ]);
    }

    public function testUpdate()
    {
        $this->expect('PUT', 'objects', [
            'objectID' => 0,
            'id' => 'id',
            'firstname' => 'tamer',
            'lastname' => 'ashkar'
        ]);

        $this->ontraport->shouldReceive('item')->once();

        $this->resource()->update([
            'id' => 'id',
            'firstname' => 'tamer',
            'lastname' => 'ashkar'
        ]);

        $this->expect('PUT', 'objects', [
            'objectID' => 0,
            'firstname' => 'tamer',
            'lastname' => 'ashkar',
            'id' => 'id',
        ]);

        $this->ontraport->shouldReceive('item')->once();

        $this->resource()->update('id', [
            'firstname' => 'tamer',
            'lastname' => 'ashkar'
        ]);
    }

    public function testDelete()
    {
        $this->expect('DELETE', 'object', [
            'objectID' => 0,
            'id' => 'id',
        ]);

        $this->ontraport->shouldReceive('item')->once();

        $this->resource()->delete('id');
    }

    public function testDeleteWhere()
    {
        $this->expect('DELETE', 'objects', [
            'objectID' => 0,
            'performAll' => true,
            'condition' => json_encode([[
                'field' => ['field' => 'firstname'], 'op' => '=', 'value' => ['value' => 'tamer']
            ]]),
        ]);

        $this->assertTrue($this->resource()->where('firstname', 'tamer')->delete());
    }

    public function testDeleteMany()
    {
        $this->expect('DELETE', 'objects', [
            'objectID' => 0,
            'ids' => 'id1,id2',
        ]);

        $this->assertTrue($this->resource()->delete(['id1', 'id2']));
    }

    public function testSaveOrUpdate()
    {
        $this->expect('POST', 'objects/saveorupdate', [
            'objectID' => 0,
            'firstname' => 'tamer',
            'lastname' => 'ashkar'
        ]);

        $this->ontraport->shouldReceive('item')->once();

        $this->resource()->saveOrUpdate([
            'firstname' => 'tamer',
            'lastname' => 'ashkar'
        ]);
    }

    public function testInfo()
    {
        $this->expect('GET', 'objects/getInfo', [
            'objectID' => 0
        ]);

        $this->resource()->info();
    }

    public function testMeta()
    {
        $this->expect('GET', 'objects/meta', [
            'objectID' => 0
        ]);

        $this->resource()->meta();
    }

    public function testMetaFormattedByName()
    {
        $this->expect('GET', 'objects/meta', [
            'objectID' => 0,
            'format' => 'byName'
        ]);

        $this->resource()->meta([
            'format' => 'byName'
        ]);
    }

    public function testTag()
    {
        $this->expect('PUT', 'objects/tag', [
            'objectID' => 0,
            'ids' => 'id1,id2',
            'add_list' => 'tag1,tag2',
        ]);

        $this->resource()->tag(['id1', 'id2'], ['tag1', 'tag2']);
    }

    public function testTagByName()
    {
        $this->expect('PUT', 'objects/tagByName', [
            'objectID' => 0,
            'ids' => ['id1','id2'],
            'add_names' => ['tagName1','tagName2'],
        ]);

        $this->resource()->tagByName(['id1','id2'], ['tagName1','tagName2']);
    }

    public function testUntag()
    {
        $this->expect('DELETE', 'objects/tag', [
            'objectID' => 0,
            'ids' => 'id1,id2',
            'remove_list' => 'tag1,tag2'
        ]);

        $this->resource()->untag(['id1', 'id2'], ['tag1', 'tag2']);
    }

    protected function resource()
    {
        return $this->newResource(OntraportObject::class, 0);
    }
}
