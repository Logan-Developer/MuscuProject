<?php

namespace App\Controller;

use App\Entity\ContactRequests;
use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index()
    {
        $ContactRequests = new ContactRequests();

        $contactForm = $this->createForm(ContactFormType::class, $ContactRequests);

        return $this->render('contact/index.html.twig',[
            'contact_form'=> $contactForm->createView(),
        ]);
    }
}
