<?php

namespace Wsio\Ontraport;

use ArrayAccess;
use Wsio\Ontraport\Contracts\Client;
use Wsio\Ontraport\Resources\Object;
use Wsio\Ontraport\Clients\CurlClient;

class Ontraport implements ArrayAccess
{
    /**
     * The Ontraport client.
     *
     * @var \Wsio\Ontraport\Contracts\Client
     */
    protected $client;

    /**
     * The Ontraport objects.
     *
     * @var \Wsio\Ontraport\Objects
     */
    protected $objects;

    /**
     * The default list of Ontraport objects.
     *
     * @var array
     */
    protected $defaults = [
        'contacts' => 0,
        'tasks' => 1,
        'staff' => 2,
        'sequences' => 5,
        'rules' => 6,
        'messages' => 7,
        'subscribers' => 8,
        'notes' => 12,
        'blasts' => 13,
        'tags' => 14,
        'products' => 16,
        'purchases' => 17,
        'fulfillments' => 19,
        'landingPages' => 20,
        'customObjects' => 99,
    ];

    /**
     * The extension list of Ontraport objects.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Create a new Ontraport instance.
     *
     * @param  mixed  $id
     * @param  mixed  $key
     * @param  \Wsio\Ontraport\Contracts\Client|null $client
     * @return  void
     */
    public function __construct($id, $key, $client = null)
    {
        $this->objects = new Objects($this->defaults);
        $this->client = $client ?: new CurlClient($id, $key);

        $this->addDefaultExtensions();
    }

    /**
     * Create a new Ontraport response.
     *
     * @param  mixed  $resource
     * @param  array  $response
     * @return \Wsio\Ontraport\Response
     */
    public function respond($resource, $response)
    {
        return new Response($resource, $response);
    }

    /**
     * Create a new item from the response.
     *
     * @param  mixed  $resource
     * @param  array  $response
     * @return \Wsio\Ontraport\Fluent
     */
    public function item($resource, $response)
    {
        return $this->respond($resource, $response)->item();
    }

    /**
     * Create a new collection from the response.
     *
     * @param  mixed  $resource
     * @param  array  $response
     * @return array
     */
    public function collection($resource, $response)
    {
        return $this->respond($resource, $response)->collection();
    }

    /**
     * Create a new GET request to Ontraport.
     *
     * @param  string  $uri
     * @param  array   $data
     * @return array
     */
    public function get($uri, array $data)
    {
        return $this->client->request('GET', $uri, $data);
    }

    /**
     * Create a new POST request to Ontraport.
     *
     * @param  string  $uri
     * @param  array   $data
     * @return array
     */
    public function post($uri, array $data = [])
    {
        return $this->client->request('POST', $uri, $data);
    }

    /**
     * Create a new PUT request to Ontraport.
     *
     * @param  string  $uri
     * @param  array   $data
     * @return array
     */
    public function put($uri, array $data = [])
    {
        return $this->client->request('PUT', $uri, $data);
    }

    /**
     * Create a new DELETE request to Ontraport.
     *
     * @param  string  $uri
     * @param  array   $data
     * @return array
     */
    public function delete($uri, array $data = [])
    {
        return $this->client->request('DELETE', $uri, $data);
    }

    /**
     * Retrieve an Ontraport object.
     *
     * @param  mixed  $object
     * @return mixed
     */
    public function object($object)
    {
        // We will check for a custom object, which allows developers to create
        // objects using their own customized object Closure to create it.
        // Otherwise, we'll check to see if an object exists by default.

        if (array_key_exists($object, $this->extensions)) {
            return $this->extensions[$object]($this);
        }

        $object = is_numeric($object) ? $object : $this->objects->find($object);

        return new Object($this, $object);
    }

    /**
     * Extend an Ontraport object.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @return $this
     */
    public function extend($name, $value)
    {
        if (is_callable($value)) {
            $this->extensions[$name] = $value;
        } else {
            $this->extensions[$name] = function ($ontraport) use ($value) {
                return new $value($ontraport);
            };
        }

        return $this;
    }

    /**
     * Add the default Ontraport object extensions.
     *
     * @return void
     */
    protected function addDefaultExtensions()
    {
        // @todo:
        // $this->extend('tasks', Task::class);
    }

    /**
     * Return the Ontraport Objects instance.
     *
     * @return array
     */
    public function objects()
    {
        return $this->objects;
    }

    /**
     * Set the Ontraport client.
     *
     * @param  \Wsio\Ontraport\Contracts\Client  $client
     * @return void
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Return the Ontraport client.
     *
     * @return \Wsio\Ontraport\Contracts\Client
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * Dynamically call an Ontraport object.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->object($method);
    }

    /**
     * Dynamically retrieve an Ontraport object.
     *
     * @param  string  $property
     * @return mixed
     */
    public function __get($property)
    {
        return $this->object($property);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param  mixed  $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Set the value for a given offset.
     *
     * @param  mixed  $offset
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
}
