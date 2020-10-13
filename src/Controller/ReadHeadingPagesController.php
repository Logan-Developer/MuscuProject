<?php

namespace App\Controller;

use App\Repository\HeadingPagesRepository;
use App\Repository\HeadingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReadHeadingPagesController extends AbstractController
{
    /**
     * @Route("/read", name="see_headings")
     */
    public function index(HeadingsRepository $repository)
    {

        $headings = $repository->findAll();

        return $this->render('read_heading_pages/index.html.twig', [
            'headings' => $headings
        ]);
    }



    /**
     * @Route("/read/heading/{id}", name="see_heading_pages")
     */
    public function seeHeadingPages(HeadingsRepository $repository, $id)
    {

        $heading = $repository->findOneBy(['id' => $id]);

        return $this->render('read_heading_pages/heading_pages.html.twig', [
            'heading' => $heading
        ]);
    }



    /**
     * @Route("/read/heading/page/{id}", name="read_heading_page")
     */
    public function readHeadingPage(HeadingPagesRepository $repository, $id)
    {

        $headingPage = $repository->findOneBy(['id' => $id]);

        return $this->render('read_heading_pages/read_heading_page.html.twig', [
            'headingPage' => $headingPage
        ]);
    }
}
