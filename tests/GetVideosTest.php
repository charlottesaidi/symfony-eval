<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetVideosTest extends WebTestCase
{
    public function testVideosRequest()
    {
        $client = self::createClient();
        $client->request('GET', '/api/videos');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
