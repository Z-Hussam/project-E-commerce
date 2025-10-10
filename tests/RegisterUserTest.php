<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');
        $client->submitForm('Validez', [
            'inscription[firstname]' => 'Sam',
            'inscription[lastname]' => 'Sam1',
            'inscription[email]' => 'email@email.com',
            'inscription[plainPassword][first]' => '123456',
            'inscription[plainPassword][second]' => '123456',

        ]);
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        $this->assertSelectorExists('div:contains("Votre compte est créé, veuillez vous conneceter.")');
    }
    // il faut creer une base donnée pour le test => symfony console doctrine:database:create --env=test
    // symfony console doctrine:migrations:migrate -n --env=test
}
