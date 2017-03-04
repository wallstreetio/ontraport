<?php

namespace Wsio\Ontraport\Contracts;

interface Client
{
    /**
     * Send an API request to Ontraport.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $data
     * @return array
     */
    public function request($method, $uri, array $data = []);
}
