<?php

namespace Queskey\FrontEndBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{
<<<<<<< HEAD
    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/search');
=======
    public function testFilter()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/searchFilter');
>>>>>>> origin/akshat
    }

}
