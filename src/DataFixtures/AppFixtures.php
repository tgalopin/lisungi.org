<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Helper;
use App\Entity\HelpRequest;
use App\MatchFinder\Locality;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadAdmin($manager);
        $this->loadHelpers($manager);
        $this->loadHelpRequests($manager);

        $manager->flush();
    }

    private function loadAdmin(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->username = 'tgalopin';
        $admin->setPassword($this->encoder->encodePassword($admin, 'password'));

        $manager->persist($admin);
    }

    private function loadHelpers(ObjectManager $manager)
    {
        $helpers = [
            [
                'firstName' => 'Elizabeth',
                'lastName' => 'Gregory',
                'email' => 'elizabeth.gregory@example.com',
                'phone' => '022 281 4578',
                'locality' => Locality::LOCALITIES['fr_CD']['KALAMU'],
                'company' => 'Citipo',
                'masks' => 10,
                'glasses' => 0,
                'blouses' => 0,
                'gel' => 500,
                'gloves' => 0,
                'soap' => 0,
                'food' => null,
                'other' => null,
            ],
            [
                'firstName' => 'Linette',
                'lastName' => 'Fremont',
                'email' => 'linette.fremont@example.com',
                'phone' => '022 281 4578',
                'locality' => Locality::LOCALITIES['fr_CD']['MALUKU'],
                'company' => null,
                'masks' => 0,
                'glasses' => 10,
                'blouses' => 0,
                'gel' => 0,
                'gloves' => 25,
                'soap' => 0,
                'food' => 'Riz et poulet',
                'other' => null,
            ],
        ];

        foreach ($helpers as $data) {
            $helper = new Helper();
            $helper->firstName = $data['firstName'];
            $helper->lastName = $data['lastName'];
            $helper->email = $data['email'];
            $helper->phone = $data['phone'];
            $helper->locality = $data['locality'];
            $helper->company = $data['company'];
            $helper->masks = $data['masks'];
            $helper->glasses = $data['glasses'];
            $helper->blouses = $data['blouses'];
            $helper->gel = $data['gel'];
            $helper->gloves = $data['gloves'];
            $helper->soap = $data['soap'];
            $helper->food = $data['food'];
            $helper->other = $data['other'];

            $manager->persist($helper);
        }
    }

    private function loadHelpRequests(ObjectManager $manager)
    {
        $requests = [
            [
                'helpType' => HelpRequest::TYPE_MASKS,
                'ownerUuid' => 'cd34e489-ec5a-4fb7-8fa5-e36554f1cd6c',
                'firstName' => 'Jeanne',
                'lastName' => 'Martin',
                'email' => 'jeanne.martin@example.com',
                'phone' => '022 281 4578',
                'organization' => null,
                'locality' => Locality::LOCALITIES['fr_CD']['MALUKU'],
                'quantity' => 5,
            ],
            [
                'helpType' => HelpRequest::TYPE_GLOVES,
                'ownerUuid' => 'cd34e489-ec5a-4fb7-8fa5-e36554f1cd6c',
                'firstName' => 'Jeanne',
                'lastName' => 'Martin',
                'email' => 'jeanne.martin@example.com',
                'phone' => '022 281 4578',
                'organization' => null,
                'locality' => Locality::LOCALITIES['fr_CD']['MALUKU'],
                'quantity' => 5,
            ],
            [
                'helpType' => HelpRequest::TYPE_GEL,
                'ownerUuid' => '4c4813df-ac99-4484-9cde-fdda1a7a910d',
                'firstName' => 'Catherine',
                'lastName' => 'Lambert',
                'email' => 'catherine.lambert@example.com',
                'phone' => '022 281 4578',
                'organization' => 'Citipo',
                'locality' => Locality::LOCALITIES['fr_CD']['KALAMU'],
                'quantity' => 300,
            ],
        ];

        foreach ($requests as $data) {
            $request = new HelpRequest();
            $request->type = $data['helpType'];
            $request->ownerUuid = Uuid::fromString($data['ownerUuid']);
            $request->firstName = $data['firstName'];
            $request->lastName = $data['lastName'];
            $request->email = $data['email'];
            $request->locality = $data['locality'];
            $request->phone = $data['phone'];
            $request->quantity = $data['quantity'];

            $manager->persist($request);
        }
    }
}
