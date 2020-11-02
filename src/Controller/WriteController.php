<?php

namespace App\Controller;

use App\Entity\HeadingPages;
use App\Entity\Headings;
use App\Form\write\AddModifyHeadingPagesType;
use App\Form\write\AddModifyHeadingType;
use App\Repository\HeadingPagesRepository;
use App\Repository\HeadingsRepository;
use App\Repository\UsersRepository;
use DateTime;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class WriteController extends AbstractController
{
    /**
     * @Route("/write", name="write", methods={"GET", "POST"})
     */
    public function index(HeadingsRepository $repository, Request $request)
    {
        $heading = new Headings();
        $addHeadingForm = $this->createForm(AddModifyHeadingType::class, $heading);

        $addHeadingForm->handleRequest($request);
        if ($addHeadingForm->isSubmitted()) {

            if ($addHeadingForm->isValid()) {

                try {

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($heading);
                    $entityManager->flush();

                    $this->addFlash('success', 'Rubrique ajoutée avec succès!');

                } catch (Exception $exception) {

                    $this->addFlash('danger', 'Erreur, une rubrique de même titre est déjà existante!');
                }

            } else {

                $this->addFlash('danger', 'Erreur, veuillez réessayer plus tard.');
            }
        }

        $headings = $repository->findAll();

        return $this->render('write/index.html.twig',[
            'headings' => $headings,
            'add_heading_form' => $addHeadingForm->createView()
        ]);
    }



    /**
     * @Route("/write/modify/heading/{id}", name="modify_heading", methods={"GET", "PUT", "POST"})
     */
    public function modifyHeading(HeadingsRepository $headingsRepository, HeadingPagesRepository $headingPagesRepository, UsersRepository $usersRepository, $id, Request $request)
    {
        $heading = $headingsRepository->findOneBy(['id'=>$id]);
        $headingPages = $headingPagesRepository->findAll();
        $headingPage = new HeadingPages();

        $addHeadingPageForm = $this->createForm(AddModifyHeadingPagesType::class, $headingPage);

        $modifyHeadingForm = $this->createForm(AddModifyHeadingType::class, $heading, [
            'titleHeading' => $heading->getTitleHeading(),
            'method' => 'PUT'
        ]);

        if ($request->request->has("add_modify_heading_pages")) {

            $addHeadingPageForm->handleRequest($request);

            if ($addHeadingPageForm->isSubmitted()) {

                $headingPage->setHeading($heading);

                if ($addHeadingPageForm->isValid()) {

                    // save the heading page in the database

                    try {

                        $redactor = $usersRepository->findOneBy(['username' => $this->getUser()->getUsername()]);

                        $headingPage->setRedactor($redactor);
                        $headingPage->setModificationDate(new DateTime());
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($headingPage);
                        $entityManager->flush();

                        $this->addFlash('success', 'Article ajouté avec succès!');

                    } catch (Exception $exception) {

                        $this->addFlash('danger', 'Erreur, un article de même titre est déjà existant!');
                    }

                } else {

                    $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
                }
            }



        } else {

            $modifyHeadingForm->handleRequest($request);

            if ($modifyHeadingForm->isSubmitted()) {


                if ($modifyHeadingForm->isValid()) {

                    // save the modified heading in the database

                    try {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($heading);
                        $entityManager->flush();

                        $this->addFlash('success', 'Informations modifiées avec succès!');

                    } catch (Exception $exception) {

                        $this->addFlash('danger', 'Erreur, une rubrique de même titre est déjà existante!');
                    }

                } else {

                    $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
                }
            }
        }


        return $this->render('write/modify_heading.html.twig', [
            'heading' => $heading,
            'headingPages' => $headingPages,
            'modify_heading_form' => $modifyHeadingForm->createView(),
            'add_heading_page_form' => $addHeadingPageForm->createView()
        ]);
    }



    /**
     * @Route("/write/modify/page/{id}", name="modify_heading_page", methods={"GET", "PUT"})
     */
    public function modifyHeadingPage(HeadingPagesRepository $repository, $id, Request $request)
    {
        $headingPage = $repository->findOneBy(['id' => $id]);

        $modifyHeadingPageForm = $this->createForm(AddModifyHeadingPagesType::class, $headingPage, [
            'titleHeadingPage'=> $headingPage->getTitlePage(),
            'contentHeadingPage'=> $headingPage->getContentPage(),
            'method' => 'PUT'
        ]);

        $modifyHeadingPageForm->handleRequest($request);

        if ($modifyHeadingPageForm->isSubmitted()) {

            if ($modifyHeadingPageForm->isValid()) {

                // save the modified page in the database

                try {

                    $headingPage->setModificationDate(new DateTime());
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($headingPage);
                    $entityManager->flush();

                    $this->addFlash('success', 'Article modifié avec succès!');

                } catch (Exception $exception) {

                    $this->addFlash('danger', 'Erreur, un article de même titre est déjà existant!');
                }

            } else {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }
        }


        return $this->render('write/modify_heading_page.html.twig', [
            'headingPage' => $headingPage,
            'modify_heading_page_form' => $modifyHeadingPageForm->createView()
        ]);
    }



    /**
    * @Route("/write/delete/heading/{id}", name="delete_heading", methods={"DELETE"})
    */
    public function deleteHeading(HeadingsRepository $repository, $id)
    {
        $heading = $repository->findOneBy(['id' => $id]);

        if ($heading != null) {

            try {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($heading);
                $entityManager->flush();

                $this->addFlash('success', 'La rubrique a été supprimée avec succès!');
            } catch (ForeignKeyConstraintViolationException $e) {

                $this->addFlash('danger', 'Erreur, impossible de supprimer une rubrique contenant des articles!');

            } catch (Exception $e) {

                $this->addFlash('danger', 'Une erreur est survenue, merci de réessayer plus tard.');
            }

        }

        return $this->redirectToRoute('write');
    }



    /**
     * @Route("/write/delete/page/{id}", name="delete_heading_page", methods={"DELETE"})
     */
    public function deleteHeadingPage(HeadingPagesRepository $repository, $id)
    {
        // TODO delete the page only if the redactor is the owner or if it's an admin
        $headingPage = $repository->findOneBy(['id' => $id]);
        $heading = $headingPage->getHeading();

        if ($headingPage != null) {

            $entityManager = $this->getDoctrine()->getManager();

            $heading->removeHeadingPage($headingPage);
            $entityManager->persist($heading);
            $entityManager->flush();

            $entityManager->remove($headingPage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('modify_heading', ['id' => $heading->getId()]);
    }
}