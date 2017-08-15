<?php

namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\Request;

class JSONRequestParserService
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
     * @param bool $assoc
     * @return mixed
     */
    public function getContent($assoc = true)
    {
        return json_decode($this->request->getContent(), $assoc);
    }
}