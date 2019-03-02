<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GradinskiCentarController extends AbstractController
{
    /**
     * @Route("/gradinski-centar", name="gradinski_centar")
     */
    public function index()
    {
        return $this->render('gradinski_centar/index.html.twig');
    }

    /**
     * @Route("/gradinski-centar/raztenie-za-vkashti", name="za_vkashti")
     */
    public function zaVkashti()
    {
        return $this->render('gradinski_centar/za-vkashti.html.twig');
    }

    /**
     * @Route("/gradinski-centar/galeria", name="gradinski_centar_galeria")
     */
    public function gardenCenterGallery()
    {
        return $this->render('gradinski_centar/gallery_gc.html.twig');
    }


}
