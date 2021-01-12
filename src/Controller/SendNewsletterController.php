<?php

namespace App\Controller;

use App\Form\SendNewsletterFormType;
use App\Repository\UsersRepository;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SendNewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter", name="send_newsletter")
     */
    public function index(UsersRepository $repository, Request $request, Swift_Mailer $mailer)
    {
        // build the form
        $users = $repository->findAll();
        $sendNewsletterForm = $this->createForm(SendNewsletterFormType::class);

        // handle the submit
        $sendNewsletterForm->handleRequest($request);

        if ($sendNewsletterForm->isSubmitted()) {

            if ($sendNewsletterForm->isValid()) {

                $mail = $sendNewsletterForm->getData();

                // send the newsletter to all subscribers
                $mailAddresses = [];
                foreach ($users as $user) {
                    array_push($mailAddresses, $user->getEmail());
                }

                $message = (new Swift_Message($mail['messageTitle']))
                    ->setFrom($this->getParameter('mailer.mail'), 'MuscuProject')
                    ->setTo($mailAddresses)
                    ->setBody($mail['message'], 'text/html');

                $mailer->send($message);

                $this->addFlash('success', 'Newsletter envoyée avec succès à tous les abonnés!');

            } else {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }
        }


        return $this->render('send_newsletter/index.html.twig', [
            'send_newsletter_form'=> $sendNewsletterForm->createView()
        ]);
    }
}
