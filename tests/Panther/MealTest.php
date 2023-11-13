<?php

//src/tests/Panther/PantherTestCase.php

namespace App\Tests\Panther;

use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\Client;

class MealTest extends PantherTestCase
{

    public function testFoodAdded()
    {
        $client = Client::createChromeClient();
        $client->followRedirects();
        $client->request('GET', 'http://diet_gwb/meal');
        $this->assertPageTitleContains('Meals');

        $crawler = $client->clickLink('edit');
        $this->assertPageTitleContains('Edit Meal');
        $rteCount = $crawler->filter('#mealid')->attr('data-rte');
        $client->executeScript("document.querySelector('#meal_pantry td').click()");
        $client->waitForAttributeToContain('#mealid', 'data-rte', $rteCount + 1);

        $client->executeScript("document.querySelector('#ready_foods td').click()");
        $client->waitForAttributeToContain('#mealid', 'data-rte', $rteCount);
    }
}
