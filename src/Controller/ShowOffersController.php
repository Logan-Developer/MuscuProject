<?php

namespace App\Controller;

use App\Repository\OffersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowOffersController extends AbstractController
{
    /**
     * @Route("/show/offers", name="show_offers")
     */
    public function index(OffersRepository $repository): Response
    {

        $offers = $repository->findAll();

        return $this->render('show_offers/index.html.twig', [
            'offers' => $offers
        ]);
    }
}
