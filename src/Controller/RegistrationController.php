<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        // go to the home screen if the user is logged in
        if ($this->getUser() != null) {

            return $this->redirectToRoute('home');
        }



        // build the form
        $user = new Users();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);

        // handle the submit
        $registrationForm->handleRequest($request);

        $error = false;
        $msg = null;

        if ($registrationForm->isSubmitted()) {

            if ($registrationForm->isValid()) {

                // encode the password
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);

                // save the user in the database

                try {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $msg = 'Inscription réussie, vous pouvez dès à présent vous connecter.';

                } catch (Exception $exception) {

                    $error = true;
                    $msg = 'Erreur, l\'adresse mail ou le pseudo entré(e) est déjà utilisé(e) par un autre utilisateur.';
                }

            } else {

                $error = true;
                $msg = 'Une erreur est survenue, merci de réessayer plus tard.';
            }
        }


        return $this->render('registration/index.html.twig', [
            'registration_form'=> $registrationForm->createView(),
            'error' => $error,
            'msg' => $msg,
        ]);
    }
}
