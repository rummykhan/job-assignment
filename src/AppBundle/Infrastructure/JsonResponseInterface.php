<?php

namespace AppBundle\Infrastructure;


interface JsonResponseInterface
{
    /**
     * To have consistency in the app, wrote a wrapper on $this->json()
     *
     * @param bool $success
     * @param int $status
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendJsonResponse($success = true, $status = 200, array $data = []);
}