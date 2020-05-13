<?php

namespace App\Tests\Controller;

use App\Entity\HelpRequest;
use App\MatchFinder\Locality;
use App\Repository\HelpRequestRepository;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HelpRequestControllerTest extends WebTestCase
{
    public function testRequest()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/process/j-ai-besoin-d-aide');
        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Envoyer ma demande');
        $this->assertCount(1, $button);

        $form = $button->form();
        $form->setValues([
            'composite_help_request[firstName]' => 'Titouan',
            'composite_help_request[lastName]' => 'Galopin',
            'composite_help_request[email]' => 'titouan.galopin@example.com',
            'composite_help_request[phone]' => '022 281 4578',
            'composite_help_request[locality]' => Locality::LOCALITIES['fr_CD']['KALAMU'],
            'composite_help_request[organization]' => 'Citipo',
        ]);

        // gets the raw values
        $values = $form->getPhpValues();

        // adds fields to the raw values
        // see https://symfony.com/doc/current/testing.html#adding-and-removing-forms-to-a-collection
        $values['composite_help_request']['details'] = [
            HelpRequest::TYPE_MASKS => ['need' => true, 'quantity' => 1, 'details' => ''],
            HelpRequest::TYPE_GLASSES => ['need' => true, 'quantity' => 2, 'details' => ''],
            HelpRequest::TYPE_BLOUSES => ['need' => true, 'quantity' => 3, 'details' => ''],
            HelpRequest::TYPE_GEL => ['need' => true, 'quantity' => 4, 'details' => ''],
            HelpRequest::TYPE_GLOVES => ['need' => true, 'quantity' => 5, 'details' => ''],
            HelpRequest::TYPE_SOAP => ['need' => true, 'quantity' => 6, 'details' => ''],
            HelpRequest::TYPE_FOOD => ['need' => true, 'quantity' => '', 'details' => ''],
            HelpRequest::TYPE_OTHER => ['need' => true, 'quantity' => '', 'details' => 'Other'],
        ];

        // submits the form with the existing and new values
        $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        $expectedRequests = [
            ['type' => HelpRequest::TYPE_MASKS, 'quantity' => 1, 'details' => ''],
            ['type' => HelpRequest::TYPE_GLASSES, 'quantity' => 2, 'details' => ''],
            ['type' => HelpRequest::TYPE_BLOUSES, 'quantity' => 3, 'details' => ''],
            ['type' => HelpRequest::TYPE_GEL, 'quantity' => 4, 'details' => ''],
            ['type' => HelpRequest::TYPE_GLOVES, 'quantity' => 5, 'details' => ''],
            ['type' => HelpRequest::TYPE_SOAP, 'quantity' => 6, 'details' => ''],
            ['type' => HelpRequest::TYPE_FOOD, 'quantity' => '', 'details' => ''],
            ['type' => HelpRequest::TYPE_OTHER, 'quantity' => '', 'details' => 'Other'],
        ];

        /** @var HelpRequest[] $requests */
        $requests = self::$container->get(HelpRequestRepository::class)->findBy(['email' => 'titouan.galopin@example.com']);

        foreach ($requests as $key => $request) {
            $this->assertInstanceOf(HelpRequest::class, $request);
            $this->assertSame('Titouan', $request->firstName);
            $this->assertSame('Galopin', $request->lastName);
            $this->assertSame(Locality::LOCALITIES['fr_CD']['KALAMU'], $request->locality);
            $this->assertSame('titouan.galopin@example.com', $request->email);
            $this->assertSame('022 281 4578', $request->phone);
            $this->assertSame($expectedRequests[$key]['type'], $request->type);
            $this->assertSame($expectedRequests[$key]['quantity'], $request->quantity);
            $this->assertSame($expectedRequests[$key]['details'], $request->details);
        }
    }

    public function testRequesterViewDelete()
    {
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
}
