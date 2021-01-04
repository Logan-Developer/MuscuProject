<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Form\AddModifyOfferType;
use App\Repository\OffersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ManageOffersController extends AbstractController
{
    /**
     * @Route("/manageoffers/", name="manage_offers", methods={"GET", "POST"})
     */
    public function index(OffersRepository $repository, Request $request, EntityManagerInterface $entityManager)
    {
        $offer = new Offers();
        $addOfferForm = $this->createForm(AddModifyOfferType::class, $offer);

        $addOfferForm->handleRequest($request);

        if ($addOfferForm->isSubmitted()) {

            if ($addOfferForm->isValid()) {

                // save the offer in the database

                try {
                    $entityManager->persist($offer);
                    $entityManager->flush();

                    $this->addFlash('success', 'Offre ajoutée avec succès!');

                } catch (Exception $exception) {

                    $this->addFlash('danger', 'Erreur, une offre de même nom est déjà existante.');
                }

            } else {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }
        }

        $offers = $repository->findAll();

        return $this->render('manage_offers/index.html.twig', [
            'offers' => $offers,
            'add_offer_form' => $addOfferForm->createView()
        ]);
    }





    /**
     * @Route("/manageoffers/delete/{id}", name="delete_offer", methods={"DELETE"})
     */
    public function deleteOffer(OffersRepository $repository, $id, EntityManagerInterface $entityManager)
    {
        $offer = $repository->findOneBy(['id' => $id]);



        if ($offer != null) {

            try {

                $entityManager->remove($offer);
                $entityManager->flush();

                $this->addFlash('success', 'Offre supprimée avec succès!');
            } catch (Exception $e) {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }

        }

        return $this->redirectToRoute('manage_offers');
    }





    /**
     * @Route("/manageoffers/modify/{id}", name="modify_offer", methods={"GET", "PUT"})
     */
    public function modifyOffer(OffersRepository $repository, Request $request, $id, EntityManagerInterface $entityManager)
    {
        $offer = $repository->findOneBy(['id'=>$id]);

        $modifyOfferForm = $this->createForm(AddModifyOfferType::class, $offer, [
            'method' => 'PUT'
        ]);

        $modifyOfferForm->handleRequest($request);

        if ($modifyOfferForm->isSubmitted()) {

            if ($modifyOfferForm->isValid()) {

                // save the offer in the database

                try {
                    $entityManager->persist($offer);
                    $entityManager->flush();

                    $this->addFlash('success', 'Offre modifiée avec succès!');

                } catch (Exception $exception) {

                    $this->addFlash('danger', 'Erreur, une offre de même nom est déjà existante.');
                }

            } else {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }

            return $this->redirectToRoute('modify_offer', ['id' => $offer->getId()]);
        }

        return $this->render('manage_offers/modify.html.twig', [
            'offer' => $offer,
            'modify_offer_form' => $modifyOfferForm->createView()
        ]);
    }
}