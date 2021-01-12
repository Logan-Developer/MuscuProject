<?php

namespace App\Controller;

use App\Repository\OffersRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowOffersController extends AbstractController
{
    /**
     * @Route("/offers", name="show_offers")
     */
    public function index(OffersRepository $repository): Response
    {

        $offers = $repository->findAll();

        return $this->render('show_offers/index.html.twig', [
            'offers' => $offers
        ]);
    }

    /**
     * @Route("/offers/subscribe/{id}", name="subscribe_offer")
     */
    public function subscribe(OffersRepository $offersRepository, UsersRepository $usersRepository, $id, EntityManagerInterface $entityManager): Response
    {
        $offer = $offersRepository->findOneBy(['id'=>$id]);
        $user = $usersRepository->findOneBy(['username'=>$this->getUser()->getUsername()]);

        try {

            $user->setOffer($offer);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Félicitations, vous êtes désormais abonné!');
            return $this->redirectToRoute('home');

        } catch (Exception $e) {
            $this->addFlash('danger', 'Erreur, abonnement impossible, veuillez réessayer plus tard.');
        }

        return $this->render('show_offers/index.html.twig', [
            'offer' => $offer
        ]);
    }
}
