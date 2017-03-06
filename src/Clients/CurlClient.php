<?php

namespace Wsio\Ontraport\Clients;

use Wsio\Ontraport\Contracts\Client;
use Wsio\Ontraport\Exceptions\InvalidRequest;

class CurlClient implements Client
{
    /**
     * The Ontraport key.
     *
     * @var mixed
     */
    protected $key;

    /**
     * The Ontraport secrect.
     *
     * @var mixed
     */
    protected $secret;

    /**
     * The Ontraport base API path.
     *
     * @var string
     */
    protected static $path = 'https://api.ontraport.com/1';

    /**
     * Create a new Ontraport Curl Client instance.
     *
     * @param  mixed  $key
     * @param  mixed  $secret
     * @return void
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * Send an API request to Ontraport.
     *
     * @see https://github.com/Ontraport/ontra_api_examples/blob/master/contacts_add_contact.php
     * @param  string  $method
     * @param  string  $uri
     * @param  array   $data
     * @return array
     */
    public function request($method, $uri, array $data = array())
    {
        $path = $this->path() . '/' . trim($uri, '/');

        $session = curl_init();

        // Curious as why i have to do this
        if ($method === 'PUT') {
            curl_setopt($session, CURLOPT_POSTFIELDS, http_build_query($data));
        } else {
            $path .= '?' . http_build_query($data);
        }

        curl_setopt($session, CURLOPT_HEADER, true);
        curl_setopt($session, CURLOPT_HTTPHEADER, $this->defaultHeaders());
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($session, CURLOPT_POST, $method !== 'GET');

        curl_setopt($session, CURLOPT_URL, $path);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
        $response = curl_exec($session);
        $statusCode = curl_getinfo($session, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($session, CURLINFO_HEADER_SIZE);
        $body = substr($response, $headerSize);

        if ($statusCode !== 200) {
            throw new InvalidRequest($body);
        }

        curl_close($session);

        return json_decode($body, true);
    }

    /**
     * Retrieve the default Ontraport headers.
     *
     * @return array
     */
    protected function defaultHeaders()
    {
        return [
            'Api-Appid:' . $this->key,
            'Api-Key:' . $this->secret
        ];
    }

    /**
     * Return the Ontraport API base path.
     *
     * @return void
     */
    protected static function path()
    {
        return static::$path;
    }
}
