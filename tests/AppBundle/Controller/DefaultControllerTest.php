<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testSuccessfulLogin()
    {
        $client = static::createClient();

        $client->request('GET', '/', [], [], ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testLogout()
    {
        $client = static::createClient();

        $client->request('GET', '/', [], [], ['PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'admin']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $client->request('GET', '/logout');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }
}
