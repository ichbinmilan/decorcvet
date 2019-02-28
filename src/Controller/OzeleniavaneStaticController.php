<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OzeleniavaneStaticController extends AbstractController
{
    /**
     * @Route("/ozeleniavane", name="ozeleniavane")
     */
    public function index()
    {
        return $this->render('ozeleniavane/index.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/proektirane", name="proektirane")
     */
    public function proektirane()
    {
        return $this->render('ozeleniavane/proektirane.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/polivni-sistemi", name="polivni_sistemi")
     */
    public function polivni()
    {
        return $this->render('ozeleniavane/polivni.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/izpalnenie", name="izpalnenie")
     */
    public function izpalnenie()
    {
        return $this->render('ozeleniavane/izpalnenie.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/poddrujjka", name="poddrujjka")
     */
    public function poddrujjka()
    {
        return $this->render('ozeleniavane/poddrujjka.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/kak-rabotim", name="kak_rabotim")
     */
    public function kakRabotim()
    {
        return $this->render('ozeleniavane/kak-rabotim.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/ceni-srokove", name="ceni_srokove")
     */
    public function ceniSrokove()
    {
        return $this->render('ozeleniavane/ceni-srokove.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/firma", name="zashto_firma")
     */
    public function zashtoFirma()
    {
        return $this->render('ozeleniavane/zashto-firma.html.twig', [
        ]);
    }
}
