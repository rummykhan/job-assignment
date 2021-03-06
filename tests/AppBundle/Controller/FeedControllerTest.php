<?php

namespace Tests\AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FeedControllerTest extends WebTestCase
{
    public function testFeedResponse()
    {
        $client = static::createClient();

        $client->request('GET', '/', [], [], ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = json_encode([
            'url' => 'http://pf.tradetracker.net/?aid=1&type=xml&encoding=utf-8&fid=251713&categoryType=2&additionalType=2',
            'limit' => '10'
        ]);

        $client->request('POST', '/feed', [], [], ['CONTENT_TYPE' => 'application/json'], $content);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
    }

    public function testURLFailedValidation()
    {
        $client = static::createClient();

        $client->request('GET', '/', [], [], ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = json_encode([
            'url' => null
        ]);

        $client->request('POST', '/feed', [], [], ['CONTENT_TYPE' => 'application/json'], $content);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }

    public function testFeedLimit()
    {
        $client = static::createClient();

        $client->request('GET', '/', [], [], ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        foreach ([10, 20, 50, 100] as $range) {

            $content = json_encode([
                'url' => 'http://pf.tradetracker.net/?aid=1&type=xml&encoding=utf-8&fid=251713&categoryType=2&additionalType=2',
                'limit' => $range
            ]);

            $client->request('POST', '/feed', [], [], ['CONTENT_TYPE' => 'application/json'], $content);

            $response = json_decode($client->getResponse()->getContent(), true);

            $this->assertArrayHasKey('success', $response);
            $this->assertArrayHasKey('data', $response);

            $this->assertCount($range, $response['data']);
        }
    }
}