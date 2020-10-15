<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\my_account\AccountInfosFormType;
use App\Form\my_account\ChangePasswordType;
use App\Form\my_account\SubscribeNewsletterType;
use App\Repository\UsersRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MyAccountController extends AbstractController
{
    /**
     * @Route("/myaccount", name="my_account", methods={"GET", "PUT"})
     */
    public function index(Request $request, UsersRepository $repository, LoginFormAuthenticator $authenticator, UserPasswordEncoderInterface $encoder)
    {

        $username = $this->getUser()->getUsername();
        $user = $repository->findOneBy(['username' => $username]);

        $newsletterSubscriber = $repository->findOneBy(['newsletterSubscriber'=>$user->getNewsletterSubscriber()])->getNewsletterSubscriber();


        // build the forms
        // setting the actual username and email as default values

        $changeAccountInfosForm = $this->createForm(AccountInfosFormType::class, $user, [
            'username' => $username,
            'email' => $user->getEmail(),
            'method' => 'PUT'
        ]);

        // handle the submit
        if ($request->request->has('account_infos_form')) {
            $changeAccountInfosForm->handleRequest($request);

            if ($changeAccountInfosForm->isSubmitted()) {

                if ($changeAccountInfosForm->isValid()) {

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();

                    $this->addFlash('success', 'Les informations ont bien été mises à jour!');

                } else {

                    $this->addFlash('danger', 'Une erreur est survenue, veuillez réessayer plus tard.');
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
                    $entityManager->flush();

                    $this->addFlash('success', 'Le mot de passe a bien été mis à jour!');

                } else {

                    $this->addFlash('danger', 'Une erreur est survenue, veuillez vérifier les mots de passe, et réessayez plus tard.');
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
                    $entityManager->flush();

                    // actualise the form with the new newsletter subscription state
                    if (!$newsletterSubscriber) {

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

                    $this->addFlash('success', 'Le statut de votre abonnement à la newsletter a bien été modifié!');

                } else {

                    $this->addFlash('danger', 'Une erreur est survenue, veuillez réessayer plus tard.');
                }
            }
        }

        return $this->render('my_account/index.html.twig', [
            'account_infos_form' => $changeAccountInfosForm->createView(),
            'password_form' => $changePasswordForm->createView(),
            'newsletter_form' => $newslettersSubscriptionForm->createView(),
            'subscription_state_msg' => $subscriptionStateMsg
        ]);
    }
}
