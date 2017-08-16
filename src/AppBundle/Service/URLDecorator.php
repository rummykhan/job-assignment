<?php

namespace AppBundle\Service;

/**
 * Purpose of this service is to carefully attach skip and limit to the URL
 *
 * Class URLDecorator
 * @package AppBundle\Service
 */
class URLDecorator
{
    private $postData = null;

    /**
     * @param string $urlKey
     * @param string $limit
     * @param int $perPage
     * @return string
     * @throws \Exception
     */
    public function decorate($perPage = 10, $urlKey = 'url', $limit = 'limit')
    {
        if (!$this->postData) {
            throw new \Exception("Post data not set.");
        }

        $url = $this->get($urlKey);
        $limit = $this->get($limit) ?: 50;

        return "{$url}&limit={$limit}";
    }

    /**
     * @param $postData
     * @return $this
     */
    public function setPostData($postData)
    {
        $this->postData = $postData;
        return $this;
    }

    /**
     * @param $key
     * @return null
     */
    private function get($key)
    {
        return $this->postData[$key] ?? null;
    }

}