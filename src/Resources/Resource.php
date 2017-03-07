<?php

namespace Wsio\Ontraport\Resources;

use Wsio\Ontraport\Ontraport;

class Resource
{
    /**
     * The Ontraport instance.
     *
     * @var \Wsio\Ontraport\Ontraport
     */
    protected $ontraport;

    /**
     * The start position for the request.
     *
     * @var int
     */
    protected $start;

    /**
     * The number of items to return for the request.
     *
     * @var int
     */
    protected $range;

    /**
     * The sort field.
     *
     * @var string
     */
    protected $sort;

    /**
     * The sort direction.
     *
     * @var string
     */
    protected $sortDirection;

    /**
     * The string to search for in the object.
     *
     * @var string
     */
    protected $search;

    /**
     * Boolean flag to additionally search Object Notes.
     *
     * @var bool
     */
    protected $searchNotes;

    /**
     * The query constraints.
     *
     * @var array
     */
    protected $condition;

    /**
     * The resource namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The singular resource namespace.
     *
     * @var string
     */
    protected $singularNamespace;

    /**
     * Create a new Ontraport resouce.
     *
     * @param  \Wsio\Ontraport\Ontraport  $ontraport
     * @return void
    */
    public function __construct(Ontraport $ontraport)
    {
        $this->ontraport = $ontraport;
    }

    /**
     * Find an Ontraport resource.
     *
     * @param  int  $id
     * @return \Wsio\Ontraport\Fluent
     */
    public function find($id)
    {
        $response = $this->ontraport->get(
            $this->getSingularNamespace(), $this->toArray(compact('id'))
        );

        return $this->ontraport->item($this, $response);
    }

    /**
     * Retrieve the first item in an Ontraport collection.
     *
     * @return \Wsio\Ontraport\Fluent|null
     */
    public function first()
    {
        $response = $this->limit(1)->get();

        return isset($response[0]) ? $response[0] : null;
    }

    /**
     * Retrieve a collection of Ontraport resources.
     *
     * @param  array  $parameters
     * @return array
     */
    public function get(array $parameters = array())
    {
        $response = $this->ontraport->get(
            $this->getNamespace(), $this->toArray($parameters)
        );

        return $this->ontraport->collection($this, $response);
    }

    /**
     * Create a new Ontraport resource.
     *
     * @param  array  $data
     * @return \Wsio\Ontraport\Fluent
     */
    public function create(array $data)
    {
        $response = $this->ontraport->post(
            $this->getNamespace(), $this->toArray($data)
        );

        return $this->ontraport->item($this, $response);
    }

    /**
     * Update an Ontraport resource.
     *
     * @param  mixed  $data
     * @param  array|null $override
     * @return \Wsio\Ontraport\Fluent
     */
    public function update($data, $override = null)
    {
        if (func_num_args() == 2) {
            $override['id'] = $data;
            $data = $override;
        }

        $response = $this->ontraport->put(
            $this->getNamespace(), $this->toArray($data)
        );

        return $this->ontraport->item($this, $response);
    }

    /**
     * Delete one or more Ontraport resources.
     *
     * @param  mixed  $id
     * @return \Wsio\Ontraport\Fluent
     */
    public function delete($id = null)
    {
        if (is_array($id)) {
            return $this->deleteMany($id);
        }

        $namespace = $id ? $this->getSingularNamespace() : $this->getNamespace();

        $response = $this->ontraport->delete(
            $namespace, $this->toArray(compact('id'))
        );

        return $this->ontraport->item($this, $response);
    }

    /**
     * Delete a list of Ontraport resources.
     *
     * @param  array  $ids
     * @return \Wsio\Ontraport\Fluent
     */
    public function deleteMany(array $ids)
    {
        $parameters = [
            'ids' => implode(',', $ids)
        ];

        $response = $this->ontraport->delete(
            $this->getNamespace(), $this->toArray($parameters)
        );

        return $this->ontraport->item($this, $response);
    }

    /**
     * Create or update an Ontraport resource.
     *
     * @param  array  $data
     * @return \Wsio\Ontraport\Fluent
     */
    public function saveOrUpdate(array $data)
    {
        $namespace = $this->getNamespace() . '/saveorupdate';

        $response = $this->ontraport->post(
            $namespace, $this->toArray($data)
        );

        return $this->ontraport->item($this, $response);
    }

    /**
     * Retrieve information about an Ontraport resource.
     *
     * @return array
     */
    public function info()
    {
        return $this->ontraport->get('objects/getInfo', $this->toArray());
    }

    /**
     * Retrieve meta about an Ontraport resource.
     *
     * @param  array  $data
     * @return array
     */
    public function meta(array $data = [])
    {
        return $this->ontraport->get('objects/meta', $this->toArray($data));
    }

    /**
     * The start/from position for the request.
     *
     * @param  int  $amount
     * @return $this
     */
    public function start($amount)
    {
        $this->start = (int) $amount;

        return $this;
    }

    /**
     * The start/from position for the request.
     *
     * @param  int  $amount
     * @return $this
     */
    public function from($amount)
    {
        return $this->start($amount);
    }

    /**
     * The range/limit of items for the request.
     *
     * @param  int  $amount
     * @return $this
     */
    public function range($amount)
    {
        $this->range = (int) $amount;

        return $this;
    }

    /**
     * The range/limit of items for the request.
     *
     * @param  int  $amount
     * @return $this
     */
    public function limit($amount)
    {
        return $this->range($amount);
    }

    /**
     * The string to search for.
     *
     * @param  string  $string
     * @return $this
     */
    public function search($string)
    {
        $this->search = (string) $string;

        return $this;
    }

    /**
     * Boolean flag to additionally search Object Notes for the Search term given in Search parameter.
     *
     * @param  bool  $active
     * @return $this
     */
    public function searchNotes($active = true)
    {
        $this->searchNotes = $active;

        return $this;
    }

    /**
     * The field and direction to sort by.
     *
     * @param  string  $field
     * @param  string $direction
     * @return $this
     */
    public function orderBy($field, $direction = 'asc')
    {
        $this->sort = $field;
        $this->sortDirection = $direction;

        return $this;
    }

    /**
     * Sort by descending order based on the field.
     *
     * @param  string $field
     * @return $this
     */
    public function orderByDesc($field)
    {
        return $this->orderBy($field, 'desc');
    }

    /**
     * Add a constraint to the request query.
     *
     * @param  string  $field
     * @param  string  $operator
     * @param  string  $value
     * @param  string $boolean
     * @return $this
     */
    public function where($field, $operator = null, $value = null, $boolean = 'AND')
    {
        // If the developer is adding multiple where statements together,
        // we will enforce that by adding the approriate boolean.

        if (count($this->condition) > 0) {
            $this->condition[] = $boolean;
        }

        // Here we will make some assumptions about the operator. If only two values
        // are passed in, we will assume the operator is an equals sign. Furthermore,
        // we will identify if the value is null and apply the respective operator.

        list($value, $operator) = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() == 2
        );

        $this->condition[] = [
            'field' => compact('field'),
            'op' => $operator,
            'value' => $value
        ];

        return $this;
    }

    /**
     * Add an or constraint to the request query.
     *
     * @param  string  $field
     * @param  string  $operator
     * @param  string  $value
     * @param  string $boolean
     * @return $this
     */
    public function orWhere($field, $operator = null, $value = null)
    {
        $this->where($field, $operator, $value, 'OR');

        return $this;
    }

    /**
     * Add a null constraint to the request query.
     *
     * @param  string  $field
     * @param  string  $boolean
     * @return $this
     */
    public function whereNull($field, $boolean = 'and')
    {
        return $this->where($field, null);
    }

    /**
     * Add a where in constraint to the request query.
     *
     * @param  string $field
     * @param  array $values
     * @return $this
     */
    public function whereIn($field, $values)
    {
        $list = array_map(function ($value) {
            return compact('value');
        }, $values);

        $this->condition[] = [
            'field' => compact('field'),
            'op' => '=',
            'value' => compact('list')
        ];

        return $this;
    }

    /**
     * Retrieve the value an operator of the conditional.
     *
     * @param  mixed  $value
     * @param  mixed  $operator
     * @param  bool   $useDefault
     * @return array
     */
    protected function prepareValueAndOperator($value, $operator, $useDefault = false)
    {
        if ($useDefault) {
            $value = $operator;
            $operator = '=';
        }

        if (is_null($value)) {
            $value = 'NULL';
            $operator = 'IS';
        } else {
            $value = compact('value');
        }

        return [$value, $operator];
    }

    /**
     * Retrieve the default request parameters.
     *
     * @param  array  $data
     * @return array
     */
    public function toArray(array $data = [])
    {
        return array_filter(array_merge([
            'start' => $this->start,
            'range' => $this->range,
            'sort' => $this->sort,
            'sortDir' => $this->sortDirection,
            'search' => $this->search,
            'searchNotes' => $this->searchNotes,
            'condition' => $this->condition ? json_encode($this->condition) : null
        ], $data), function ($value) {
            return $value !== null;
        });
    }

    /**
     * Retrieve the namespace of the resource.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Retrieve the singular namespace of the resource.
     *
     * @return string
     */
    public function getSingularNamespace()
    {
        return $this->singularNamespace ?: rtrim($this->getNamespace(), 's');
    }
}
