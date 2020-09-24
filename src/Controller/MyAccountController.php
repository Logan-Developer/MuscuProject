<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AccountInfosFormType;
use App\Form\ChangePasswordType;
use App\Form\SubscribeNewsletterType;
use App\Repository\UsersRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="my_account")
     */
    public function index(Request $request, UsersRepository $repository, LoginFormAuthenticator $authenticator, UserPasswordEncoderInterface $encoder)
    {

        $username = $this->getUser()->getUsername();
        $user = $repository->findOneBy(['username' => $username]);


        $error = false;
        $msgChangeAccount = null;
        $msgChangePassword = null;
        $msgNewsletterSubscribe = null;
        $newsletterSubscriber = $repository->findOneBy(['newsletterSubscriber'=>$user->getNewsletterSubscriber()])->getNewsletterSubscriber();


        // build the forms
        // setting the actual username and email as default values

        $changeAccountInfosForm = $this->createForm(AccountInfosFormType::class, $user, [
            'username' => $username,
            'email' => $user->getEmail()
        ]);

        // handle the submit
        if ($request->request->has('account_infos_form')) {
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
        }





        $userFromForm = new Users(); // User with infos retrieved from the form
        $changePasswordForm = $this->createForm(ChangePasswordType::class, $userFromForm);

        if ($request->request->has('change_password')) {
            $changePasswordForm->handleRequest($request);

            if ($changePasswordForm->isSubmitted()) {

                if ($changePasswordForm->isValid() && $authenticator->checkCredentials(['password'=>$changePasswordForm->get('actual_password')->getData()], $user)) {

                    // encode the new password and push the new password in the database
                    $user->setPassword($encoder->encodePassword($user, $changePasswordForm->get('password')->getData()));
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $msgChangePassword = 'Le mot de passe a bien été mis à jour';

                } else {

                    $error = true;
                    $msgChangePassword = 'Une erreur est survenue, veuillez vérifier les mots de passe, et réessayez plus tard.';
                }
            }
        }




        if ($newsletterSubscriber) {

            $subscriptionButtonState = 'Se désabonner';
            $subscriptionStateMsg = 'Abonné(e)';
        }

        else {

            $subscriptionButtonState = 'S\'abonner';
            $subscriptionStateMsg = 'Non abonné(e)';
        }

        $newslettersSubscriptionForm = $this->createForm(SubscribeNewsletterType::class, $user,
            ['subscription_button_state'=> $subscriptionButtonState
        ]);


        if($request->request->has('subscribe_newsletter')) {
            $newslettersSubscriptionForm->handleRequest($request);

            if ($newslettersSubscriptionForm->isSubmitted()) {

                if ($newslettersSubscriptionForm->isValid()) {

                    $user->setNewsletterSubscriber(!$newsletterSubscriber);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    if ($newsletterSubscriber) {

                        $subscriptionButtonState = 'Se désabonner';
                        $subscriptionStateMsg = 'Abonné(e)';
                    }

                    else {

                        $subscriptionButtonState = 'S\'abonner';
                        $subscriptionStateMsg = 'Non abonné(e)';
                    }

                    $newslettersSubscriptionForm = $this->createForm(SubscribeNewsletterType::class, $user,
                        ['subscription_button_state'=> $subscriptionButtonState
                        ]);

                    $msgNewsletterSubscribe = 'Le statut de votre abonnement à la newsletter a bien été modifié!';

                } else {

                    $error = true;
                    $msgNewsletterSubscribe = 'Une erreur est survenue, veuillez réessayer plus tard.';
                }
            }
        }

        return $this->render('my_account/index.html.twig', [
            'account_infos_form' => $changeAccountInfosForm->createView(),
            'password_form' => $changePasswordForm->createView(),
            'newsletter_form' => $newslettersSubscriptionForm->createView(),
            'error' => $error,
            'msg_account' => $msgChangeAccount,
            'msg_password' => $msgChangePassword,
            'msg_newsletter' => $msgNewsletterSubscribe,
            'subscription_state_msg' => $subscriptionStateMsg
        ]);
    }
}
