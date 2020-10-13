<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AddModifyUserType;
use App\Repository\UsersRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ManageUsersController extends AbstractController
{

    // TODO filters for users and pagination

    /**
     * @Route("/manageusers", name="manage_users")
     */
    public function index(UsersRepository $repository, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {

        $user = new Users();
        $addUserForm = $this->createForm(AddModifyUserType::class, $user);

        $error = false;
        $msg = null;

        $addUserForm->handleRequest($request);

        if ($addUserForm->isSubmitted()) {

            if ($addUserForm->isValid()) {

                // encode the password
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setChangePassword(true); // the user have to change his password when he will connect next time.

                // save the user in the database

                try {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $msg = 'Inscription réussie, l\'utilisateur peut désormais se connecter!';

                } catch (Exception $exception) {

                    $error = true;
                    $msg = 'Erreur, l\'adresse mail ou le pseudo entré(e) est déjà utilisé(e) par un autre utilisateur.';
                }

            } else {

                $error = true;
                $msg = 'Une erreur est survenue, merci de réessayer plus tard.';
            }
        }

        $users = $repository->findAll();

        return $this->render('manage_users/index.html.twig', [
            'users' => $users,
            'add_user_form' => $addUserForm->createView(),
            'error' => $error,
            'msg' => $msg
        ]);
    }





    /**
     * @Route("/manageusers/delete/{id}", name="delete_user")
     */
    public function deleteUser(UsersRepository $repository, $id)
    {

        $admins = $repository->findByRole('ROLE_ADMIN');

        $user = $repository->findOneBy(['id' => $id]);

        // there must be at least one admin registered
        if (!in_array('ROLE_ADMIN', $user->getRoles()) or count($admins) > 1) {

            if ($user != null) {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($user);
                $entityManager->flush();

            }
        }

        return $this->redirectToRoute('manage_users');
    }





    /**
     * @Route("/manageusers/modify/{id}", name="modify_user")
     */
    public function modifyUser(UsersRepository $repository, Request $request, $id)
    {

        $admins = $repository->findByRole('ROLE_ADMIN');

        $user = $repository->findOneBy(['id'=>$id]);

        $error = false;
        $msg = null;

        $modifyUserForm = $this->createForm(AddModifyUserType::class, $user, [
            'addingUser' => false,
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ]);

        $modifyUserForm->handleRequest($request);



        if ($modifyUserForm->isSubmitted()) {

            // there must be at least one admin registered
            if (in_array('ROLE_ADMIN', $user->getRoles()) or count($admins) > 1) {
                if ($modifyUserForm->isValid()) {

                    // save the user in the database

                    try {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($user);
                        $entityManager->flush();

                        $msg = 'Informations modifiées avec succès!';

                    } catch (Exception $exception) {

                        $error = true;
                        $msg = 'Erreur, l\'adresse mail ou le pseudo entré(e) est déjà utilisé(e) par un autre utilisateur.';
                    }

                } else {

                    $error = true;
                    $msg = 'Une erreur est survenue, merci de réessayer plus tard.';
                }
            } else {

                $error = true;
                $msg = 'Erreur, il doit y avoir au moins un administrateur enregistré!';
            }
        }

        return $this->render('manage_users/modify.html.twig', [
            'user' => $user,
            'modify_user_form' => $modifyUserForm->createView(),
            'error' => $error,
            'msg' => $msg
        ]);
    }





    /**
     * @Route("/manageusers/resetPassword/{id}", name="reset_password")
     */
    public function resetPassword(UsersRepository $repository, $id)
    {

        $user = $repository->findOneBy(['id'=>$id]);

        if ($user != null) {

            $entityManager = $this->getDoctrine()->getManager();
            $user->setChangePassword(true);
            $entityManager->flush();

        }

        return $this->redirectToRoute('manage_users');
    }
}