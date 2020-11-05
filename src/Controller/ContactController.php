<?php

namespace App\Controller;

use App\Entity\ContactRequests;
use App\Form\ContactFormType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"GET", "POST"})
     */
    public function index(Request $request, Swift_Mailer $mailer)
    {
        // build the form
        $contactRequest = new ContactRequests();
        $contactForm = $this->createForm(ContactFormType::class, $contactRequest);

        // handle the submit
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted()) {

            if ($contactForm->isValid()) {


                // send the contact request

                    $message = (new Swift_Message($contactRequest->getMessageTitle()))
                        ->setFrom($contactRequest->getEmail())
                        ->setTo('MAILER_ADDRESS')
                        ->setBody($contactRequest->getMessage(), 'text/html');

                    $mailer->send($message);

                    $this->addFlash('success', 'Demande de contact envoyée avec succès!');

            } else {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }
        }


        return $this->render('contact/index.html.twig', [
            'contact_form'=> $contactForm->createView(),
        ]);
    }
}
