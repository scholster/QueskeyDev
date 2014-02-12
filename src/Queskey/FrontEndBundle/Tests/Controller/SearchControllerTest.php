<?php

namespace Queskey\FrontEndBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
    public function testFilter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/searchFilter');
    }

}
