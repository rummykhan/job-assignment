<?php

namespace AppBundle\Service;


class URLDecoratorService
{
    private $postData = null;

    /**
     * @param string $urlKey
     * @param string $pageKey
     * @param int $perPage
     * @return string
     * @throws \Exception
     */
    public function decorate($urlKey = 'url', $pageKey = 'page', $perPage = 10)
    {
        if (!$this->postData) {
            throw new \Exception("Post data not set.");
        }

        $url = $this->get($urlKey);
        $page = $this->get($pageKey) ?: 1;
        $skip = ($page - 1) * $perPage;
        $limit = $perPage;

        return "{$url}&skip={$skip}&limit={$limit}";
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