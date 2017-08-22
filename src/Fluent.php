<?php

namespace Wsio\Ontraport;

use ArrayAccess;
use JsonSerializable;
use Wsio\Ontraport\Resources\Resource;

class Fluent implements ArrayAccess, JsonSerializable
{
    /**
     * The resource instance.
     *
     * @var \Wsio\Ontraport\Resources\Resource
     */
    protected $resource;

    /**
     * All of the attributes set on the container.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Create a new Fluent instance.
     *
     * @param  \Wsio\Ontraport\Resources\Resource  $resource
     * @param  mixed  $attributes
     * @return void
     */
    public function __construct(Resource $resource, $attributes = null)
    {
        $this->resource = $resource;
        $this->attributes = $attributes;
    }

    /**
     * Update the resource.
     *
     * @return \Wsio\Ontraport\Fluent
     */
    public function update()
    {
        return $this->resource->update($this->toArray());
    }

    /**
     * Update the resource.
     *
     * @return \Wsio\Ontraport\Fluent
     */
    public function save()
    {
        return $this->update();
    }

    /**
     * Delete the resource.
     *
     * @return \Wsio\Ontraport\Fluent
     */
    public function delete()
    {
        return $this->resource->delete($this->id);
    }

    /**
     * Returns an attribute of the resource.
     *
     * @param  mixed  $attribute
     * @param  mixed  $default
     * @return mixed
     */
    public function get($attribute = null, $default = null)
    {
        if (is_null($attribute)) {
            return $this->attributes;
        }

        if (array_key_exists($attribute, $this->attributes)) {
            return $this->attributes[$attribute];
        }

        return $default;
    }

    /**
     * Converts resource attributes to array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->get();
    }

    /**
     * Convert the resource into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert the Fluent instance to JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param  string  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * Get the value for a given offset.
     *
     * @param  string  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * Set the value at the given offset.
     *
     * @param  string  $offset
     * @param  mixed   $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param  string  $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->get($property);
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param  string  $property
     * @param  mixed  $value
     * @return void
     */
    public function __set($property, $value)
    {
        $this->attributes[$property] = $value;
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param  string  $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param  string  $key
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }
}
