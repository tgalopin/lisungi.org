<?php

namespace App\Tests\Controller;

use App\Entity\Helper;
use App\MatchFinder\Locality;
use App\Repository\HelperRepository;
use App\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HelperControllerTest extends WebTestCase
{
    public function testHelperCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/process/je-peux-aider');
        $this->assertResponseIsSuccessful();

        $button = $crawler->selectButton('Envoyer ma proposition');
        $this->assertCount(1, $button);

        $client->submit($button->form(), [
            'helper[firstName]' => 'Titouan',
            'helper[lastName]' => 'Galopin',
            'helper[locality]' => Locality::LOCALITIES['fr_CD']['KALAMU'],
            'helper[email]' => 'titouan.galopin@example.com',
            'helper[phone]' => '022 281 4578',
            'helper[company]' => 'Citipo',
            'helper[masks]' => 1,
            'helper[glasses]' => 2,
            'helper[blouses]' => 3,
            'helper[gel]' => 4,
            'helper[gloves]' => 5,
            'helper[soap]' => 6,
            'helper[food]' => 'Food',
            'helper[other]' => 'Other',
            'helper[confirm]' => true,
            'helper[confirm_legal]' => true,
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $client->followRedirect();
        $this->assertResponseIsSuccessful();

        /** @var Helper $helper */
        $helper = self::$container->get(HelperRepository::class)->findOneBy(['email' => 'titouan.galopin@example.com']);
        $this->assertInstanceOf(Helper::class, $helper);
        $this->assertSame('Titouan', $helper->firstName);
        $this->assertSame('Galopin', $helper->lastName);
        $this->assertSame('titouan.galopin@example.com', $helper->email);
        $this->assertSame('022 281 4578', $helper->phone);
        $this->assertSame(Locality::LOCALITIES['fr_CD']['KALAMU'], $helper->locality);
        $this->assertSame('Citipo', $helper->company);
        $this->assertSame(1, $helper->masks);
        $this->assertSame(2, $helper->glasses);
        $this->assertSame(3, $helper->blouses);
        $this->assertSame(4, $helper->gel);
        $this->assertSame(5, $helper->gloves);
        $this->assertSame(6, $helper->soap);
        $this->assertSame('Food', $helper->food);
        $this->assertSame('Other', $helper->other);
    }

    public function testHelperRequiresConfirm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/process/je-peux-aider');
        $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $button = $crawler->selectButton('Envoyer ma proposition');
        $this->assertCount(1, $button);

        $client->submit($button->form(), [
            'helper[firstName]' => 'Titouan',
            'helper[lastName]' => 'Galopin',
            'helper[locality]' => Locality::LOCALITIES['fr_CD']['KALAMU'],
            'helper[email]' => 'titouan.galopin@example.com',
            'helper[phone]' => '022 281 4578',
            'helper[company]' => 'Citipo',
            'helper[masks]' => 1,
            'helper[glasses]' => 2,
            'helper[blouses]' => 3,
            'helper[gel]' => 4,
            'helper[gloves]' => 5,
            'helper[soap]' => 6,
            'helper[food]' => 'Food',
            'helper[other]' => 'Other',
        ]);
        $this->assertResponseIsSuccessful($client->getResponse()->getStatusCode());
    }

    public function testHelperViewDelete()
    {
        $client = static::createClient();

        $helper = self::$container->get(HelperRepository::class)->findOneBy(['email' => 'elizabeth.gregory@example.com']);
        $this->assertInstanceOf(Helper::class, $helper);

        $crawler = $client->request('GET', '/process/je-peux-aider/'.$helper->getUuid()->toString());
        $this->assertResponseIsSuccessful();
        $link = $crawler->filter('a:contains(\'Supprimer ma proposition\')');
        $this->assertCount(1, $link);

        $crawler = $client->click($link->link());
        $link = $crawler->filter('a:contains(\'Oui, supprimer\')');
        $this->assertCount(1, $link);

        $client->click($link->link());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $helper = self::$container->get(HelperRepository::class)->findOneBy(['email' => 'elizabeth.gregory@example.com']);
        $this->assertNull($helper);
    }
}
