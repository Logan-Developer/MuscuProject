<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ManageUsersController extends AbstractController
{
    /**
     * @Route("/manageusers", name="manage_users")
     */
    public function index(UsersRepository $repository)
    {

        $users = $repository->findAll();

        return $this->render('manage_users/index.html.twig', [
            'users' => $users
        ]);
    }
}