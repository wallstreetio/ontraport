<?php

namespace Wsio\Tests\Ontraport\Unit\Resources;

use Wsio\Ontraport\Resources\Resource;

class ResourceTest extends TestCase
{
    public function testStart()
    {
        $query = $this->resource();

        $query->from(100);

        $this->assertEquals($query->toArray(), [
            'start' => 100
        ]);
    }

    public function testRange()
    {
        $query = $this->resource();

        $query->limit(100);

        $this->assertEquals($query->toArray(), [
            'range' => 100
        ]);
    }

    public function testOrderBy()
    {
        $query = $this->resource();

        $query->orderBy('username');

        $this->assertEquals($query->toArray(), [
            'sort' => 'username',
            'sortDir' => 'asc'
        ]);
    }

    public function testSearch()
    {
        $query = $this->resource();

        $query->search('micah')->searchNotes();

        $this->assertEquals($query->toArray(), [
            'search' => 'micah',
            'searchNotes' => true
        ]);
    }

    public function testOrderByDesc()
    {
        $query = $this->resource();

        $query->orderByDesc('username');

        $this->assertEquals($query->toArray(), [
            'sort' => 'username',
            'sortDir' => 'desc'
        ]);
    }

    public function testWhere()
    {
        $query = $this->resource();
        $query2 = $this->resource();

        $query->where('id', 1);
        $query2->where('id', '=', 1);

        $this->assertEquals($query->toArray(), $query2->toArray());
        $this->assertEquals($query->toArray(), [
            'condition' => json_encode([[
                'field' => ['field' => 'id'], 'op' => '=', 'value' => ['value' => 1]
            ]])
        ]);

        $query = $this->resource();

        $query->where('id', 1)->where('firstname', '=', 'tamer');

        $this->assertEquals($query->toArray(), [
            'condition' => json_encode([[
                'field' => ['field' => 'id'], 'op' => '=', 'value' => ['value' => 1]
            ], 'AND', [
                'field' => ['field' => 'firstname'], 'op' => '=', 'value' => ['value' => 'tamer']
            ]])
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWhereInvalidOperator()
    {
        $query = $this->resource();

        $query->where('firstname', '>', 'hi');
        $query->where('firstname', '<', 'hi');
    }

    public function testWhereNull()
    {
        $query = $this->resource();
        $query2 = $this->resource();
        $query3 = $this->resource();

        $query->whereNull('lastname');
        $query2->where('lastname', null);
        $query3->where('lastname', '=', null);

        $this->assertEquals($query->toArray(), $query2->toArray());
        $this->assertEquals($query->toArray(), $query3->toArray());
        $this->assertEquals($query->toArray(), [
            'condition' => json_encode([[
                'field' => ['field' => 'lastname'], 'op' => 'IS', 'value' => 'NULL'
            ]])
        ]);
    }

    public function testWhereIn()
    {
        $query = $this->resource();

        $query->whereIn('firstname', ['tamer', 'micah']);

        $this->assertEquals($query->toArray(), [
            'condition' => json_encode([[
                'field' => ['field' => 'firstname'], 'op' => '=', 'value' => [
                    'list' => [['value' => 'tamer'], ['value' => 'micah']]
                ]
            ]])
        ]);
    }

    public function testOrWhere()
    {
        $query = $this->resource();

        $query->where('email', '')->orWhere('email', null);

        $this->assertEquals($query->toArray(), [
            'condition' => json_encode([[
                'field' => ['field' => 'email'], 'op' => '=', 'value' => ['value' => '']
            ], 'OR', [
                'field' => ['field' => 'email'], 'op' => 'IS', 'value' => 'NULL'
            ]])
        ]);

        $query = $this->resource();

        $query->where('email', 'justsaying@fakkeee.com')
            ->orWhere('email', 'tashkar18@gmail.com');

        $this->assertEquals($query->toArray(), [
            'condition' => json_encode([[
                'field' => ['field' => 'email'], 'op' => '=', 'value' => ['value' => 'justsaying@fakkeee.com']
            ], 'OR', [
                'field' => ['field' => 'email'], 'op' => '=', 'value' => ['value' => 'tashkar18@gmail.com']
            ]])
        ]);
    }

    protected function resource()
    {
        return $this->newResource(Resource::class);
    }
}
