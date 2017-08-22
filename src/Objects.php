<?php

namespace Wsio\Ontraport;

use Wsio\Ontraport\Exceptions\InvalidCustomObject;

class Objects
{
    /**
     * The list of Ontraport objects.
     *
     * @var array
     */
    protected $objects;

    /**
     * Create a new Objects instance.
     *
     * @param  array  $objects
     * @return void
     */
    public function __construct($objects = [])
    {
        $this->objects = $objects;
    }

    /**
     * Set an Ontraport object by its name and id.
     *
     * @param  string  $object
     * @param  mixed   $id
     * @return void
     */
    public function set($object, $id = null)
    {
        if (is_array($object)) {
            return $this->setAll($object);
        }

        $this->objects[$object] = $id;
    }

    /**
     * Set the list of Ontraport objects.
     *
     * @param  array  $objects
     * @return void
     */
    public function setAll(array $objects)
    {
        $this->objects = $objects;
    }

    /**
     * Return the list of Ontraport objects.
     *
     * @return array
     */
    public function all()
    {
        return $this->objects;
    }

    /**
     * Retrieve all Ontraport objects.
     *
     * @param  string  $name
     * @return mixed
     */
    public function get($name = null)
    {
        if (is_null($name)) {
            return $this->all();
        }

        return $this->find($name);
    }

    /**
     * Find an Ontraport object by its name.
     *
     * @param  string  $name
     * @return void
     */
    public function find($name)
    {
        $name = strtolower($name);
        if (isset($this->objects[$name])) {
            return $this->objects[$name];
        }

        throw new InvalidCustomObject("The object [{$name}] does not exist.");
    }
}
