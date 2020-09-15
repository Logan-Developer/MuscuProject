<?php

namespace App\Controller;

use App\Entity\DemandesContact;
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
        $demandesContact = new DemandesContact();

        $contactForm = $this->createForm(ContactFormType::class, $demandesContact);

        return $this->render('contact/index.html.twig',[
            'contact_form'=> $contactForm->createView(),
        ]);
    }
}
