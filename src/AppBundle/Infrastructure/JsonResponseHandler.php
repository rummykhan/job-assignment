<?php

namespace AppBundle\Infrastructure;


trait JsonResponseHandler
{
    /**
     * @inheritdoc
     */
    public function sendJsonResponse($success = true, $status = 200, array $data = [])
    {
        $response = [
            'success' => $success
        ];

        if (count($data)) {
            $response['data'] = $data;
        }

        return $this->json($response, $status);
    }
}