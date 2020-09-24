<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WriteController extends AbstractController
{
    /**
     * @Route("/write", name="write")
     */
    public function index()
    {
        return $this->render('write/index.html.twig', [
            'controller_name' => 'WriteController',
        ]);
    }
}
