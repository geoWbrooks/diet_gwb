<?php

//src/path_here/NewMealTest.php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewMealTest extends WebTestCase
{
    /*
     * Does not having a class {'attr': {'class': 'js-meal-form'}} matter?
     */

    public function testNewMealForm()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/meal/new');
        $node = $crawler->selectButton('Save');
        $form = $node->form();
        $form['meal[meal_type]']->select('Lunch');
        $form['meal[date]'] = '2/24/2023';
        $client->submit($form);

        $this->assertResponseIsSuccessful();
    }

    public function testEditMealForm()
    {
        $client = static::createClient();
        $client->followRedirects();
        $client->request('GET', '/meal');
        $crawler = $client->clickLink('edit');
        $this->assertSelectorTextContains('h3', 'Edit Meal');
        $node = $crawler->selectButton('Save');
        $form = $node->form();
        $form['meal[meal_type]']->select('Lunch');
        $form['meal[date]'] = '2/24/2013';
        $client->submit($form);

        $this->assertResponseIsSuccessful();
    }

}
