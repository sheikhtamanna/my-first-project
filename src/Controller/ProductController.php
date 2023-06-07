<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CatagoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function showAll(ProductRepository $repo, CatagoryRepository $repoCat): Response
    {
        $products = $repo->findAll();
        $catagories = $repoCat->findAll();
        return $this->render('product/allProducts.html.twig', [
            'products' => $products,
            'catagories' => $catagories
        ]);
    }
    #[Route('/products/{slug}', name: 'app_products_catagory')]
    public function showByCatagory(CatagoryRepository $repo, $slug)
    {
        $catagory = $repo->findOneBy(['slug' => $slug]);
        $catagories = $repo->findAll();
        return $this->render('product/allProducts.html.twig', [
            'catagories' => $catagories,
            'products' => $catagory->getProducts()
        ]);
    }
    #[Route('/product/{id<\d+>}', name: 'app_product_show')]
    public function show($id, ProductRepository $repo)
    {
        $product = $repo->find($id);
        return $this->render('product/show.html.twig',[
            'product' => $product
        ]);
    }
   
}
