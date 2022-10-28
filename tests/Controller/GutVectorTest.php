<?php

//src/Tests/Controller/GutVectorTest.php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GutVectorTest extends WebTestCase
{

    public function testVectorCalc()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/food/vector');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h4', 'Food vectors');

        $tds = $crawler->filter('td');
//        $n = count($tds);
        $weight = $tds->eq(1);

//        $this->assertEquals(10, $n);
        $this->assertEquals(10, $weight);
    }

}
