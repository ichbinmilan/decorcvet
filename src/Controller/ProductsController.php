<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
/*    private $categoryRepo;
    private $statusRepo;
    private $productsRepo;

    public function __construct()
    {
        $this->categoryRepo = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $this->statusRepo = $this->getDoctrine()->getRepository(Status::class)->findAll();
        $this->productsRepo = $this->getDoctrine()->getRepository(Products::class)->findAll();
    }*/

    /**
     * @Route("/rastenia", name="rastenia")
     */
    public function index()
    {
        $this->categoryRepo = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $this->statusRepo = $this->getDoctrine()->getRepository(Status::class)->findAll();
        $this->productsRepo = $this->getDoctrine()->getRepository(Products::class)->findAll();
        foreach ($this->productsRepo as $product) {

        }


        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    /**
     * @Route("/rastenia/{kind}", name="rasteniaKind")
     */
    public function kind()
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    /**
     * @Route("/rastenia/{kind}/{plant}", name="rasteniaKindDetails")
     */
    public function details()
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
}
