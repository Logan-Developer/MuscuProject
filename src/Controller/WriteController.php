<?php

namespace App\Controller;

use App\Entity\HeadingPages;
use App\Entity\Headings;
use App\Form\write\AddModifyHeadingPagesType;
use App\Form\write\AddModifyHeadingType;
use App\Repository\HeadingPagesRepository;
use App\Repository\HeadingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class WriteController extends AbstractController
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
     * @Route("/write/modify/heading/{id}", name="modify_heading")
     */
    public function modifyHeading(HeadingsRepository $headingsRepository, HeadingPagesRepository $headingPagesRepository, $id, Request $request)
    {

        $heading = $headingsRepository->findOneBy(['id'=>$id]);
        $headingPages = $headingPagesRepository->findAll();
        $headingPage = new HeadingPages();

        $error = false;
        $msgAddHeadingPage = null;
        $msgModifyHeading = null;

        $addHeadingPageForm = $this->createForm(AddModifyHeadingPagesType::class, $headingPage);

        $modifyHeadingForm = $this->createForm(AddModifyHeadingType::class, $heading, [
            'titleHeading' => $heading->getTitleHeading()
        ]);

        if ($request->request->has("add_modify_heading_pages")) {

            $addHeadingPageForm->handleRequest($request);

            if ($addHeadingPageForm->isSubmitted()) {

                $headingPage->setHeading($heading);

                if ($addHeadingPageForm->isValid()) {

                    // save the heading page in the database

                    try {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($headingPage);
                        $entityManager->flush();

                        $msgAddHeadingPage = 'Article ajouté avec succès!';

                    } catch (Exception $exception) {

                        $error = true;
                        $msgAddHeadingPage = 'Erreur, un article de même titre est déjà existant!';
                    }

                } else {

                    $error = true;
                    $msgAddHeadingPage = 'Une erreur est survenue, merci de réessayer plus tard.';
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

                        $msgModifyHeading = 'Informations modifiées avec succès!';

                    } catch (Exception $exception) {

                        $error = true;
                        $msgModifyHeading = 'Erreur, une rubrique de même titre est déjà existante!';
                    }

                } else {

                    $error = true;
                    $msgModifyHeading = 'Une erreur est survenue, merci de réessayer plus tard.';
                }
            }
        }


        return $this->render('write/modify_heading.html.twig', [
            'heading' => $heading,
            'headingPages' => $headingPages,
            'modify_heading_form' => $modifyHeadingForm->createView(),
            'add_heading_page_form' => $addHeadingPageForm->createView(),
            'error' => $error,
            'msgAddHeadingPage' => $msgAddHeadingPage,
            'msgModifyHeading' => $msgModifyHeading
        ]);
    }



    /**
     * @Route("/write/modify/page/{id}", name="modify_heading_page")
     */
    public function modifyHeadingPage(HeadingPagesRepository $repository, $id, Request $request)
    {

        $headingPage = $repository->findOneBy(['id' => $id]);

        $error = false;
        $msg = null;

        $modifyHeadingPageForm = $this->createForm(AddModifyHeadingPagesType::class, $headingPage, [
            'titleHeadingPage'=> $headingPage->getTitlePage(),
            'contentHeadingPage'=> $headingPage->getContentPage(),
        ]);

        $modifyHeadingPageForm->handleRequest($request);

        if ($modifyHeadingPageForm->isSubmitted()) {

            if ($modifyHeadingPageForm->isValid()) {

                // save the modified page in the database

                try {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($headingPage);
                    $entityManager->flush();

                    $msg = 'Article modifié avec succès!';

                } catch (Exception $exception) {

                    $error = true;
                    $msg = 'Erreur, un article de même titre est déjà existant!';
                }

            } else {

                $error = true;
                $msg = 'Une erreur est survenue, merci de réessayer plus tard.';
            }
        }


        return $this->render('write/modify_heading_page.html.twig', [
            'headingPage' => $headingPage,
            'modify_heading_page_form' => $modifyHeadingPageForm->createView(),
            'error' => $error,
            'msg' => $msg
        ]);
    }



    /**
    * @Route("/write/delete/heading/{id}", name="delete_heading")
    */
    public function deleteHeading(HeadingsRepository $repository, $id)
    {

        $heading = $repository->findOneBy(['id' => $id]);

        if ($heading != null) {

            try {

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($heading);
                $entityManager->flush();

            } catch (Exception $e) {

                $this->addFlash('danger', 'Erreur, impossible de supprimer une rubrique contenant des articles!');
            }

        }

        return $this->redirectToRoute('write');
    }



    /**
     * @Route("/write/delete/page/{id}", name="delete_heading_page")
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