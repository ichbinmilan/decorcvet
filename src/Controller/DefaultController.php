<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/za-nas", name="aboutUs")
     */
    public function aboutUs()
    {
        return $this->render('aboutus.html.twig');
    }

    /**
     * @Route("/kontakti", name="contact")
     */
    public function contact()
    {
        return $this->render('contact.html.twig');
    }

    /**
     * @Route("/galeria", name="gallery")
     */
    public function gallery()
    {
        return $this->render('gallery.html.twig');
    }

    /**
     * @Route("/obshti-uslovia", name="ou")
     */
    public function ou()
    {
        return $this->render('ou.html.twig');
    }

}