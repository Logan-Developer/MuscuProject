<?php

namespace App\Controller;

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
        $contactForm = $this->createForm(ContactFormType::class);

        // handle the submit
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted()) {

            if ($contactForm->isValid()) {

                // send the contact request
                $contactRequest = $contactForm->getData();
                $message = (new Swift_Message($contactRequest->getMessageTitle()))
                    ->setFrom($contactRequest->getEmail())
                    ->setTo('logan2.humbert@gmail.com')
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
