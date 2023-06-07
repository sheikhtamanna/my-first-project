<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BasketController extends AbstractController
{
    #[Route('/basket_add_{id<\d+>}', name: 'app_basket_add')]
    public function add($id, RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();

        $basket = $session->get('basket', []);
        if(empty($basket[$id]))
        {
            $basket[$id] = 1;
        }else{
            $basket[$id]++;
        }
        $session->set("basket",$basket);
        return $this->redirectToRoute('app_basket');
    }
    
    #[Route('/basket', name: 'app_basket')]
    public function show( Request $request,RequestStack $requestStack, ProductRepository $repo, SessionInterface $session)
    {
        $session = $requestStack->getSession();
        $basket = $session->get('basket',[]);
        $dataBasket = [];
        $total = 0;
        foreach ($basket as $id => $quantity) {
            $product = $repo->find($id);
            $dataBasket[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        
        // dd($dataBasket); 
        return $this->render('basket/index.html.twig',[
            'dataBasket' => $dataBasket,
            'total' => $total
        ]);
       
    }
    
    #[Route('/basket_minus_product_{id<\d+>}', name: 'app_basket_minus_product')]
    public function removeProduct($id, RequestStack $requestStack)
    {
        $session = $requestStack->getSession();
        $basket = $session->get('basket', []);
        if(!empty($basket[$id]))
        {
            if($basket[$id] > 1)
            {
                $basket[$id]--;
            }else{

                unset($basket[$id]);
            }
        }
        $session->set('basket', $basket);
       
        return $this->redirectToRoute('app_basket');
    }
    #[Route('/basket_delete_product_{id<\d+>}', name: 'app_basket_delete_product')]
    public function deleteProduct($id, RequestStack $requestStack)
    {
        $session = $requestStack->getSession();
        $basket = $session->get('basket', []);
        if(!empty($basket[$id]))
        {
            unset($basket[$id]);
        }else{
            $this->addFlash("error","The product that you want to delete is not available");
            return $this->redirectToRoute('app_basket');
        }
        $session->set('basket', $basket);
        $this->addFlash("success","the product is successfully deleted");
        return $this->redirectToRoute('app_basket');
    }
    
}
