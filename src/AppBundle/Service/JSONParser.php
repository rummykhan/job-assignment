<?php

namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\Request;

/**
 * Purpose of this service is to get Content posted via JSON
 *
 * Class JSONRequestParser
 * @package AppBundle\Service
 */
class JSONParser
{
    /**
     * @var Request $request
     */
    protected $request = null;

    /**
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get JSON content of the request.
     *
     * @param bool $assoc
     * @return mixed
     */
    public function getContent($assoc = true)
    {
        return json_decode($this->request->getContent(), $assoc);
    }
}