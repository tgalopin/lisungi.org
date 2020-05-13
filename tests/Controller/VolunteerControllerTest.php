<?php

namespace App\Tests\Controller;

use App\Entity\Volunteer;
use App\MatchFinder\Locality;
use App\Repository\VolunteerRepository;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class VolunteerControllerTest extends WebTestCase
{
    public function testVolunteerCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/process/benevole');
        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Envoyer ma proposition');
        $this->assertCount(1, $button);

        $client->submit($button->form(), [
            'volunteer[firstName]' => 'Titouan',
            'volunteer[lastName]' => 'Galopin',
            'volunteer[locality]' => Locality::LOCALITIES['fr_CD']['KALAMU'],
            'volunteer[email]' => 'titouan.galopin@example.com',
            'volunteer[phone]' => '022 281 4578',
            'volunteer[confirm]' => true,
            'volunteer[confirm_legal]' => true,
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        /** @var Volunteer $volunteer */
        $volunteer = self::$container->get(VolunteerRepository::class)->findOneBy(['email' => 'titouan.galopin@example.com']);
        $this->assertInstanceOf(Volunteer::class, $volunteer);
        $this->assertSame('Titouan', $volunteer->firstName);
        $this->assertSame('Galopin', $volunteer->lastName);
        $this->assertSame('titouan.galopin@example.com', $volunteer->email);
        $this->assertSame('022 281 4578', $volunteer->phone);
        $this->assertSame(Locality::LOCALITIES['fr_CD']['KALAMU'], $volunteer->locality);
    }

    public function testVolunteerRequiresConfirm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/process/benevole');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Envoyer ma proposition');
        $this->assertCount(1, $button);

        $client->submit($button->form(), [
            'volunteer[firstName]' => 'Titouan',
            'volunteer[lastName]' => 'Galopin',
            'volunteer[locality]' => Locality::LOCALITIES['fr_CD']['KALAMU'],
            'volunteer[email]' => 'titouan.galopin@example.com',
            'volunteer[phone]' => '022 281 4578',
        ]);
        $this->assertResponseIsSuccessful($client->getResponse()->getStatusCode());
    }

    public function testHelperViewDelete()
    {
        $client = static::createClient();

        $helper = self::$container->get(VolunteerRepository::class)->findOneBy(['email' => 'adrien.duguet@example.com']);
        $this->assertInstanceOf(Volunteer::class, $helper);

        $crawler = $client->request('GET', '/process/benevole/'.$helper->getUuid()->toString());
        $this->assertResponseIsSuccessful();
        $link = $crawler->filter('a:contains(\'Supprimer ma proposition\')');
        $this->assertCount(1, $link);

        $crawler = $client->click($link->link());
        $link = $crawler->filter('a:contains(\'Oui, supprimer\')');
        $this->assertCount(1, $link);

        $client->click($link->link());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $helper = self::$container->get(VolunteerRepository::class)->findOneBy(['email' => 'adrien.duguet@example.com']);
        $this->assertNull($helper);
    }
}
