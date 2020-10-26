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
     * @Route("/register", name="register", methods={"GET", "POST"})
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->isPermissionValidated())
            return $this->redirectToRoute('home');

        // build the form
        $user = new Users();
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);

        // handle the submit
        $registrationForm->handleRequest($request);

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

                    $this->addFlash('success', 'Inscription réussie, vous pouvez dès à présent vous connecter.');

                } catch (Exception $exception) {

                    $this->addFlash('danger', 'Erreur, l\'adresse mail ou le pseudo entré(e) est déjà utilisé(e) par un autre utilisateur.');
                }

            } else {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }
        }


        return $this->render('registration/index.html.twig', [
            'registration_form'=> $registrationForm->createView(),
        ]);
    }

    private function isPermissionValidated() {
        return $this->getUser() === null;
    }
}
