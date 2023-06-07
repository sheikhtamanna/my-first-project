<?php

namespace App\Controller;
 
use Stripe;
use Stripe\Charge;
use App\Entity\Commande;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
        ]);
    }
 
 
    #[Route('/stripe/create-charge', name: 'app_stripe_create-charge', methods: ['POST'])]
    public function createCharge(Request $request, RequestStack $requestStack, ProductRepository $repoPro, SessionInterface $session)
    {
        $commande = new Commande();
        // on recupere utilisateur connecté
        $user = $this->getUser();
        // si aucun utilisateur n'est pas connecté en lui mettant un message flash
        if(!$user)
        {
            $this->addFlash('error', 'Veillez vous connecter afin de passer commande !');
            return $this->redirectToRoute('app_login');
        }
        $session = $requestStack->getSession();
        $basket = $session->get('basket', []);
        // si le panier est vide, on ne passe pas de commande et envoit vers la page des produits
        if(!$basket)
        {
            $this->addFlash('error', 'votre panier est vide, Aucune commande à passer !');
            return $this->redirectToRoute('app_products');
        }
        $dataBasket = [];
        $total = 0;
        foreach ($basket as $id => $quantity) {
            $product = $repoPro->find($id);
            $dataBasket[] = [
                'product' => $product,
                'quantity' => $quantity,
                'sousTotal' => $product->getPrice() * $quantity
            ];
            $total += $product->getPrice() * $quantity;
           
        }
       
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Charge::create ([
                "amount" => $total * 100,
                "currency" => "eur",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test",
                
        ]);

        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('app_stripe', [], Response::HTTP_SEE_OTHER);
    }
}