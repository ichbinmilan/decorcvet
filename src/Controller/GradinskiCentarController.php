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
//        $twigArr['big_pic'] = 'bigpic_garden-enter.jpg';
//        $twigArr['h1'] = 'ГРАДИНСКИ ЦЕНТЪР <br> ДЕКОР ЦВЕТ';
        $twigArr['h2'] = 'Снимки от градинския център';

        $twigArr['imgDir'] = 'gradinski-centar';
        $twigArr['images'] = (new Gallery($twigArr['imgDir']))->images;


        if (!$twigArr['images'] === false) {
            $this->redirectToRoute('home');
        }

        return $this->render('gallery.html.twig', $twigArr);
    }


}
