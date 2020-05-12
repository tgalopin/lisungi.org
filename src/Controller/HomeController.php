<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("", name="homepage")
     */
    public function index()
    {
        $response = $this->render('home/index.html.twig');

        $response->setCache([
            'public' => true,
            'max_age' => 900, // 15min
            's_maxage' => 900, // 15min
        ]);

        return $response;
    }
}
