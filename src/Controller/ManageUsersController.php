<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\AddModifyUserType;
use App\Repository\UsersRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ManageUsersController extends AbstractController
{

    // TODO filters for users

    /**
     * @Route("/manageusers/{page}", name="manage_users", methods={"GET", "POST"})
     */
    public function index(UsersRepository $repository, UserPasswordEncoderInterface $passwordEncoder, Request $request,
                          PaginatorInterface $paginator, $page, EntityManagerInterface $entityManager)
    {

        if (!$this->isPermissionValidated())
            return $this->redirectToRoute('home');

        $user = new Users();
        $addUserForm = $this->createForm(AddModifyUserType::class, $user);

        $addUserForm->handleRequest($request);

        if ($addUserForm->isSubmitted()) {

            if ($addUserForm->isValid()) {

                // encode the password
                $password = $passwordEncoder->encodePassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setChangePassword(true); // the user have to change his password when he will connect next time.

                // save the user in the database

                try {
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Inscription réussie, l\'utilisateur peut désormais se connecter!');

                } catch (Exception $exception) {

                    $this->addFlash('danger', 'Erreur, l\'adresse mail ou le pseudo entré(e) est déjà utilisé(e) par un autre utilisateur.');
                }

            } else {

               $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }
        }

        $users = $paginator->paginate($repository->findAll(), $page, 2);

        return $this->render('manage_users/index.html.twig', [
            'users' => $users,
            'add_user_form' => $addUserForm->createView()
        ]);
    }





    /**
     * @Route("/manageusers/delete/{id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser(UsersRepository $repository, $id, EntityManagerInterface $entityManager)
    {
        if (!$this->isPermissionValidated())
            return $this->redirectToRoute('home');

        $admins = $repository->findByRole('ROLE_ADMIN');

        $user = $repository->findOneBy(['id' => $id]);

        // there must be at least one admin registered
        if (!in_array('ROLE_ADMIN', $user->getRoles()) or count($admins) > 1) {

            if ($user != null) {

                try {

                    $entityManager->remove($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Utilisateur supprimé avec succès!');
                } catch (ForeignKeyConstraintViolationException $e) {

                    $this->addFlash('danger', 'Erreur, impossible de supprimer un rédacteur ou un administrateur ayant rédigé des articles!');

                } catch (Exception $e) {

                    $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
                }

            }
        } else {

            $this->addFlash('danger', 'Erreur, il doit y avoir au moins un administrateur enregistré!');
        }

        return $this->redirectToRoute('manage_users', ['page' => 1]);
    }





    /**
     * @Route("/manageusers/modify/{id}", name="modify_user", methods={"GET", "PUT"})
     */
    public function modifyUser(UsersRepository $repository, Request $request, $id, EntityManagerInterface $entityManager)
    {
        if (!$this->isPermissionValidated())
            return $this->redirectToRoute('home');

        $admins = $repository->findByRole('ROLE_ADMIN');

        $user = $repository->findOneBy(['id'=>$id]);

        $modifyUserForm = $this->createForm(AddModifyUserType::class, $user, [
            'addingUser' => false,
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'method' => 'PUT'
        ]);

        $modifyUserForm->handleRequest($request);



        if ($modifyUserForm->isSubmitted()) {

            // there must be at least one admin registered
            if (!in_array('ROLE_ADMIN', $user->getRoles()) or count($admins) > 1) {
                if ($modifyUserForm->isValid()) {

                    // save the user in the database

                    try {
                        $entityManager->persist($user);
                        $entityManager->flush();

                        $this->addFlash('success', 'Informations modifiées avec succès!');

                    } catch (Exception $exception) {

                        $this->addFlash('danger', 'Erreur, l\'adresse mail ou le pseudo entré(e) est déjà utilisé(e) par un autre utilisateur.');
                    }

                } else {

                    $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
                }
            } else {

                $this->addFlash('danger', 'Erreur, il doit y avoir au moins un administrateur enregistré!');
            }
        }

        return $this->render('manage_users/modify.html.twig', [
            'user' => $user,
            'modify_user_form' => $modifyUserForm->createView()
        ]);
    }





    /**
     * @Route("/manageusers/resetPassword/{id}", name="reset_password", methods={"PUT"})
     */
    public function resetPassword(UsersRepository $repository, $id, EntityManagerInterface $entityManager)
    {
        if (!$this->isPermissionValidated())
            return $this->redirectToRoute('home');

        $user = $repository->findOneBy(['id'=>$id]);

        if ($user != null) {

            $user->setChangePassword(true);
            $entityManager->flush();

            $this->addFlash('success', 'Le mot de passe de l\'utilisateur a bien été réinitialisé!');
        }

        return $this->redirectToRoute('manage_users', ['page' => 1]);
    }

    private function isPermissionValidated() {
        return $this->getUser() !== null and in_array('ROLE_ADMIN', $this->getUser()->getRoles());
    }
}