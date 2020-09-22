<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AccountInfosFormType;
use App\Form\ChangePasswordType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="my_account")
     */
    public function index(Request $request, UsersRepository $repository)
    {

        $error = false;
        $msgChangeAccount = null;
        $msgChangePassword = null;


        // build the forms
        $username = $this->getUser()->getUsername();
        $user = $repository->findOneBy(['username' => $username]);

        // setting the actual username and email as default values

        $changeAccountInfosForm = $this->createForm(AccountInfosFormType::class, $user, [
            'username' => $username,
            'email' => $user->getEmail()
        ]);

        // handle the submit
        $changeAccountInfosForm->handleRequest($request);

        if ($changeAccountInfosForm->isSubmitted()) {

            if ($changeAccountInfosForm->isValid()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $msgChangeAccount = 'Les informations ont bien été mises à jour';

            } else {

                $error = true;
                $msgChangeAccount = 'Une erreur est survenue, veuillez réessayer plus tard.';
            }
        }




        $changePasswordForm = $this->createForm(ChangePasswordType::class, $user);
        $changePasswordForm->handleRequest($request);

        if ($changePasswordForm->isSubmitted()) {

            if ($changePasswordForm->isValid() && $changePasswordForm->get('actual_password')->getData() == $this->getUser()->getPassword()) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $msgChangePassword = 'Le mot de passe a bien été mis à jour';

            } else {

                $error = true;
                $msgChangePassword = 'Une erreur est survenue, veuillez réessayer plus tard.';
            }
        }

        return $this->render('my_account/index.html.twig', [
            'account_infos_form' => $changeAccountInfosForm->createView(),
            'password_form' => $changePasswordForm->createView(),
            'error' => $error,
            'msg_account' => $msgChangeAccount,
            'msg_password' => $msgChangePassword
        ]);
    }
}
