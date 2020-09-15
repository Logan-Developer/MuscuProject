<?php

namespace App\Controller;

use App\Entity\Utilisateurs;
use App\Form\ConnectionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function index()
    {

        $utilisateurs = new Utilisateurs();

        $connectionForm = $this->createForm(ConnectionFormType::class, $utilisateurs);

        return $this->render('connexion/index.html.twig', [
            'connection_form'=> $connectionForm->createView()
        ]);
    }
}
