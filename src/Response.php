<?php

namespace Wsio\Ontraport;

class Response
{
    /**
     * Create a new Response instance.
     *
     * @param  mixed  $resource
     * @param  array  $response
     * @return void
     */
    public function __construct($resource, $response)
    {
        $this->resource = $resource;
        $this->response = $response;
    }

    /**
     * Return the code of the response.
     *
     * @return mixed
     */
    public function code()
    {
        return $this->response['code'];
    }

    /**
     * Retrieve an item from the Ontraport response.
     *
     * @return bool|\Wsio\Ontraport\Fluent
     */
    public function item()
    {
        if (!empty($this->response['data'])) {
            return new Fluent($this->resource, $this->response['data']);
        }

        // On a valid response that does not have a data field, we can assume that
        // the request is an update/delete type of request that does not return an object.

        return true;
    }

    /**
     * Retrieve an array of items from the Ontraport response.
     *
     * @return array
     */
    public function collection()
    {
        if (!empty($this->response['data'])) {
            $data = [];

            foreach ($this->response['data'] as $object) {
                $data[] = new Fluent($this->resource, $object);
            }

            return $data;
        }

        return [];
    }

    /**
     * Retrieve the Ontraport response.
     *
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }
}
