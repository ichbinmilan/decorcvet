<?php

namespace App\Controller;

use App\Gallery;
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

    /**
     * @Route("/ozeleniavane/galeria", name="galeria_proekti")
     */
    public function galleryProjects()
    {
        return $this->render('ozeleniavane/galleria-proekti.html.twig', [
        ]);
    }

    /**
     * @Route("/ozeleniavane/galeria/{kind}", name="galeria_proekti_vid")
     */
    public function galleryProjectsKind($kind)
    {
        switch ($kind) {
            case 'administrativni-sgradi':
                $twigArr['h2'] = 'Изпълнени проекти по озеленяване на административни сгради';
                $twigArr['imgDir'] = $kind;
                break;
            case 'chastni-domove':
                $twigArr['h2'] = 'Изпълнени проекти по озеленяване на частни имоти';
                $twigArr['imgDir'] = $kind;
                break;
            case 'interiorno-ozeleniavane':
                $twigArr['h2'] = 'Галерия интериорно озеленяване';
                $twigArr['imgDir'] = $kind;
                break;
            case 'proekti-3D-vizualizacii':
                $twigArr['h2'] = 'Галерия проекти с 3D визуализации';
                $twigArr['imgDir'] = $kind;
                break;
            case 'raboten-proces':
                $twigArr['h2'] = 'Галерия работен процес';
                $twigArr['imgDir'] = $kind;
                break;
            default:
                $this->redirectToRoute('home');
        }

        $twigArr['images'] = Gallery::getAllImages($twigArr['imgDir']);

        if (!$twigArr['images'] === false) {
            $this->redirectToRoute('home');
        }

        return $this->render('gallery.html.twig', $twigArr);
    }
}
