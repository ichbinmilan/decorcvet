<?php

namespace App\Controller;

use App\Gallery;
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
        $twigArr['imgDir'] = 'gradinski-centar';
        $twigArr['images'] = Gallery::getAllImages($twigArr['imgDir']);
        $twigArr['imgDir2'] = 'gradinski-centar-1';
        $twigArr['images2'] = Gallery::getAllImages($twigArr['imgDir2']);


        if (!$twigArr['images'] === false) {
            $this->redirectToRoute('home');
        }

        return $this->render('gallery2row.html.twig', $twigArr);
    }


}
