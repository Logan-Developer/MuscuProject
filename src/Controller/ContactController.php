<?php

namespace App\Controller;

use App\Entity\ContactRequests;
use App\Form\ContactFormType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request)
    {
        // build the form
        $contactRequest = new ContactRequests();
        $contactForm = $this->createForm(ContactFormType::class, $contactRequest);

        // handle the submit
        $contactForm->handleRequest($request);

        $error = false;
        $msg = null;

        if ($contactForm->isSubmitted()) {

            if ($contactForm->isValid()) {


                // save the contact request in the database

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($contactRequest);
                    $entityManager->flush();

                    $error = false;
                    $msg = 'Demande de contact envoyée avec succès!';

            } else {

                $error = true;
                $msg = 'Une erreur est survenue, merci de réessayer plus tard.';
            }
        }


        return $this->render('contact/index.html.twig', [
            'contact_form'=> $contactForm->createView(),
            'error' => $error,
            'msg' => $msg,
        ]);
    }
}
