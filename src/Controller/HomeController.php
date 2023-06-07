<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $repo): Response
    {
        // $products = $repo->findBy(['catagory' => 'T-shirt', 'color' => 'green'],['id' => 'DESC'], 5); Example - we want to collect 5 products from the last 
        $products = $repo->findBy([],["id" => "DESC"], 5);
        return $this->render('home/index.html.twig', [
            'products' => $products, 
        ]);
    }
}
