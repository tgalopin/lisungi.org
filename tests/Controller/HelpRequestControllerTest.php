<?php

namespace App\Tests\Controller;

use App\Entity\HelpRequest;
use App\Repository\HelpRequestRepository;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HelpRequestControllerTest extends WebTestCase
{
    public function testRequesterViewDelete()
    {
        $this->markTestSkipped('To reimplement');

        $client = static::createClient();

        $request = self::$container->get(HelpRequestRepository::class)->findOneBy(['email' => 'jeanne.martin@example.com']);
        $this->assertInstanceOf(HelpRequest::class, $request);

        $crawler = $client->request('GET', '/process/j-ai-besoin-d-aide/'.$request->ownerUuid->toString());
        $this->assertResponseIsSuccessful();

        $link = $crawler->filter('a:contains(\'Supprimer ma demande\')');
        $this->assertCount(1, $link);

        $crawler = $client->click($link->link());
        $link = $crawler->filter('a:contains(\'Oui, supprimer\')');
        $this->assertCount(1, $link);

        $client->click($link->link());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $request = self::$container->get(HelpRequestRepository::class)->findOneBy(['email' => 'jeanne.martin@example.com']);
        $this->assertNull($request);
    }

    public function testRequest()
    {
        $this->markTestSkipped('To reimplement');

        $client = static::createClient();

        $crawler = $client->request('GET', '/process/j-ai-besoin-d-aide');
        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Envoyer ma demande');
        $this->assertCount(1, $button);

        $form = $button->form();
        $form->setValues([
            'composite_help_request[firstName]' => 'Titouan',
            'composite_help_request[lastName]' => 'Galopin',
            'composite_help_request[zipCode]' => 75008,
            'composite_help_request[email]' => 'titouan.galopin@example.com',
            'composite_help_request[jobType]' => 'health',
            'composite_help_request[confirm]' => 1,
        ]);

        // gets the raw values
        $values = $form->getPhpValues();

        // adds fields to the raw values
        // see https://symfony.com/doc/current/testing.html#adding-and-removing-forms-to-a-collection
        $values['composite_help_request']['details'] = [
            ['helpType' => HelpRequest::TYPE_GROCERIES],
            ['helpType' => HelpRequest::TYPE_BABYSIT, 'childAgeRange' => HelpRequest::AGE_RANGE_35],
            ['helpType' => HelpRequest::TYPE_BABYSIT, 'childAgeRange' => HelpRequest::AGE_RANGE_69],
        ];

        // submits the form with the existing and new values
        $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        $help_request = self::$container->get(HelpRequestRepository::class)->findOneBy(['email' => 'titouan.galopin@example.com', 'childAgeRange' => HelpRequest::AGE_RANGE_35]);
        $this->assertInstanceOf(HelpRequest::class, $help_request);
        $this->assertSame('Titouan', $help_request->firstName);
        $this->assertSame('Galopin', $help_request->lastName);
        $this->assertSame('75008', $help_request->zipCode);
        $this->assertSame('titouan.galopin@example.com', $help_request->email);
        $this->assertSame('health', $help_request->jobType);
        $this->assertSame(HelpRequest::AGE_RANGE_35, $help_request->childAgeRange);
        $this->assertSame(HelpRequest::TYPE_BABYSIT, $help_request->helpType);

        $help_request = self::$container->get(HelpRequestRepository::class)->findOneBy(['email' => 'titouan.galopin@example.com', 'childAgeRange' => HelpRequest::AGE_RANGE_69]);
        $this->assertInstanceOf(HelpRequest::class, $help_request);
        $this->assertSame('Titouan', $help_request->firstName);
        $this->assertSame('Galopin', $help_request->lastName);
        $this->assertSame('75008', $help_request->zipCode);
        $this->assertSame('titouan.galopin@example.com', $help_request->email);
        $this->assertSame('health', $help_request->jobType);
        $this->assertSame(HelpRequest::AGE_RANGE_69, $help_request->childAgeRange);
        $this->assertSame(HelpRequest::TYPE_BABYSIT, $help_request->helpType);

        $help_request = self::$container->get(HelpRequestRepository::class)->findOneBy(['email' => 'titouan.galopin@example.com', 'helpType' => HelpRequest::TYPE_GROCERIES]);
        $this->assertInstanceOf(HelpRequest::class, $help_request);
    }
}
