<?php

namespace AppBundle\Service;

use GuzzleHttp\ClientInterface;

/**
 * This is just a wrapper around GuzzleHttp/Client to follow SOA approach and write testable code.
 *
 * Class WebClient
 * @package AppBundle\Service
 */
class WebClient
{
    /**
     * @var ClientInterface $client
     */
    private $client;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var string $method ;
     */
    private $method;

    /**
     * WebClientService constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return null|string
     */
    public function fetch()
    {
        $response = $this->client->request($this->method, $this->url);

        if ($response->getStatusCode() !== 200) {
            return null;
        }


        return $response->getBody()->getContents();
    }
}