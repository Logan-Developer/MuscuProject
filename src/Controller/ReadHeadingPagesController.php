<?php

namespace App\Controller;

use App\Repository\HeadingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReadHeadingPagesController extends AbstractController
{
    /**
     * @Route("/read", name="see_headings", methods={"GET"})
     */
    public function index(HeadingsRepository $repository)
    {

        $headings = $repository->findAll();

        return $this->render('read_heading_pages/index.html.twig', [
            'headings' => $headings
        ]);
    }



    /**
     * @Route("/read/heading/{id}", name="see_heading_pages", methods={"GET"})
     */
    public function seeHeadingPages(HeadingsRepository $repository, $id)
    {

        $heading = $repository->findOneBy(['id' => $id]);

        if ($heading == null)
            return $this->redirectToRoute('see_headings');

        return $this->render('read_heading_pages/heading_pages.html.twig', [
            'heading' => $heading
        ]);
    }
}
