<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FoodTest extends WebTestCase
{

    public function testAddFood(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/food/new');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Add new food');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'food[food_name]' => 'finger foood'
        ]);
        $client->request('GET', '/food');
        $this->assertResponseIsSuccessful();

        $newform = $crawler->selectButton('Save')->form();
        $client->submit($newform, [
            'food[food_name]' => 'finger foood'
        ]);
        $this->assertSelectorTextContains('li', 'This food already exists');
    }

}
