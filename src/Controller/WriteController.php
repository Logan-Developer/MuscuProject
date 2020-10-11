<?php

namespace App\Controller;

use App\Entity\Headings;
use App\Form\AddModifyHeadingType;
use App\Repository\HeadingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class WriteHeadingsController extends AbstractController
{
    /**
     * @Route("/write", name="write")
     */
    public function index(HeadingsRepository $repository, Request $request)
    {
        $heading = new Headings();
        $addHeadingForm = $this->createForm(AddModifyHeadingType::class, $heading);

        $error = false;
        $msg = null;

        $addHeadingForm->handleRequest($request);
        if ($addHeadingForm->isSubmitted()) {

            if ($addHeadingForm->isValid()) {

                try {

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($heading);
                    $entityManager->flush();
                    $msg = 'Rubrique ajoutée avec succès!';

                } catch (Exception $exception) {

                    $error = true;
                    $msg = 'Erreur, une rubrique de même titre est déjà existante!';
                }

            } else {

                $error = true;
                $msg = 'Erreur, veuillez réessayer plus tard.';
            }
        }

        $headings = $repository->findAll();

        return $this->render('write/index.html.twig',[
            'headings' => $headings,
            'error' => $error,
            'msg' => $msg,
            'add_heading_form' => $addHeadingForm->createView()
        ]);
    }



    /**
     * @Route("/write/modify/{id}", name="modify_heading")
     */
    public function modifyHeading(HeadingsRepository $repository, $id, Request $request)
    {

        $heading = $repository->findOneBy(['id'=>$id]);

        $error = false;
        $msg = null;

        $modifyHeadingForm = $this->createForm(AddModifyHeadingType::class, $heading, [
            'titleHeading' => $heading->getTitleHeading()
        ]);

        $modifyHeadingForm->handleRequest($request);



        if ($modifyHeadingForm->isSubmitted()) {


                if ($modifyHeadingForm->isValid()) {

                    // save the modified heading in the database

                    try {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($heading);
                        $entityManager->flush();

                        $msg = 'Informations modifiées avec succès!';

                    } catch (Exception $exception) {

                        $error = true;
                        $msg = 'Erreur, une rubrique de même titre est déjà existante!';
                    }

                } else {

                    $error = true;
                    $msg = 'Une erreur est survenue, merci de réessayer plus tard.';
                }
            }


        return $this->render('write/modify.html.twig', [
            'heading' => $heading,
            'modify_heading_form' => $modifyHeadingForm->createView(),
            'error' => $error,
            'msg' => $msg
        ]);
    }



    /**
    * @Route("/write/delete/{id}", name="delete_heading")
    */
    public function deleteHeading(HeadingsRepository $repository, $id)
    {

        // TODO delete the heading only if it is empty
        $heading = $repository->findOneBy(['id' => $id]);

        if ($heading != null) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($heading);
            $entityManager->flush();

        }

        return $this->redirectToRoute('write');
    }
}