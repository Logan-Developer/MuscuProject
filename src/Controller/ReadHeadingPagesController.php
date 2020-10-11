<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReadHeadingPagesController extends AbstractController
{
    /**
     * @Route("/read/heading/pages", name="read_heading_pages")
     */
    public function index()
    {
        return $this->render('read_heading_pages/index.html.twig', [
            'controller_name' => 'ReadHeadingPagesController',
        ]);
    }
}
