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
     * @Route("/gradinski-centar/sezonni-rastenia", name="sezonni")
     */
    public function sezonni()
    {
        return $this->render('gradinski_centar/sezonni.html.twig');
    }

    /**
     * @Route("/gradinski-centar/soliterni-rastenia", name="soliterni")
     */
    public function soliterni()
    {
        return $this->render('gradinski_centar/index.html.twig');
    }

    /**
     * @Route("/gradinski-centar/promocia", name="promo")
     */
    public function promo()
    {
        return $this->render('gradinski_centar/promo.html.twig');
    }
}
