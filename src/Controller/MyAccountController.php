<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AccountInfosFormType;
use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="my_account")
     */
    public function index()
    {

        // build the forms
        $user = new Users();
        $changeAccountInfosForm = $this->createForm(AccountInfosFormType::class, $user);
        $changePasswordForm = $this->createForm(ChangePasswordType::class, $user, [
            'username' => $user->getUsername()]);

        return $this->render('my_account/index.html.twig', [
            'account_infos_form' => $changeAccountInfosForm->createView(),
            'password_form' => $changePasswordForm->createView()
        ]);
    }
}
