<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\InscriptionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function index()
    {

        $utilisateurs = new Utilisateurs();

        $inscriptionForm = $this->createForm(InscriptionFormType::class, $utilisateurs);
        return $this->render('inscription/index.html.twig', [
            'inscription_form'=> $inscriptionForm->createView()
        ]);
    }
}
