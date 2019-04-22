<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/gradinski-centar/rastenia/{kind}", name="rasteniaVid")
     */
    public function kind($kind)
    {
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class)->findAll();
        $categoryUrl = null;
        foreach ($categoryRepo as $cat) {
            if ($cat->getUrl() === $kind) {
                $category = [
                    'id' => $cat->getId(),
                    'name' => $cat->getName(),
                    'categoryId' => $cat->getId(),
                    'pic' => $cat->getPic(),
                    'url' => $cat->getUrl(),
                ];
            }
        }
        if (empty($category)) {
            $this->redirectToRoute('home'); //toDo: 404?
        }

        $productsRepo = $this->getDoctrine()->getRepository(Products::class)->findByCategoryWithoutStatus($category['categoryId'], 5);
        if (empty($productsRepo)) {
            $products = null;
        }
        foreach ($productsRepo as $product) {
            $products[] = [
                'id' => $product->getId(),
                'name' => $product->getHead(),
                'price' => $product->getPrice() / 100,
                'pic' => $product->getPic(),
                'url' => '/gradinski-centar/rastenia/' . $product->getId() . '/' . str_replace(' ', '-', mb_strtolower($product->getHead())),
            ];
        }

        if (count($products) == 1) {
            return $this->redirectToRoute('rasteniaDetails', [
                'productId' => $products[0]['id'],
                'plant' => $products[0]['name']
            ]);
        }

        return $this->render('products/products.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/gradinski-centar/rastenia/{productId}/{plant}", name="rasteniaDetails")
     */
    public function details($productId, $plant)
    {
        $productsRepo = $this->getDoctrine()->getRepository(Products::class)->find($productId);
        if (empty($productId)) {
            $this->redirectToRoute('home'); //toDo: 404?
        }
        $statusId = $productsRepo->getStatus();
        $price = $productsRepo->getPrice() / 100;
        $statusRepo = $this->getDoctrine()->getRepository(Status::class)->find($statusId);
        if (!($statusRepo->getShowPrice())) {
            $price = 0;
        }


        $product = [
            'id' => $productsRepo->getId(),
            'status' => $statusRepo->getStatus(),
            'category' => $productsRepo->getCategory(),
            'name' => $productsRepo->getHead(),
            'price' => $price,
            'pic' => $productsRepo->getPic(),
            'desc' => $productsRepo->getDescription(),
            'cultiv' => $productsRepo->getCultiv(),
            'usage' => $productsRepo->getUsefor(),
        ];


        return $this->render('products/product_details.html.twig', [
            'product' => $product,
        ]);
    }


}
