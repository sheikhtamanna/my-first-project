<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use Stripe\Checkout\Session;
use App\Entity\CommandeDetail;
use App\Repository\ProductRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeDetailRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommandeController extends AbstractController
{
    #[Route('/make_commande', name: 'app_commande_make')]
    public function makeCommande( Request $request, RequestStack $requestStack, ProductRepository $repoPro, CommandeRepository $repoCom, CommandeDetailRepository $repoDet, EntityManagerInterface $manager): Response
    {
        $date = new DateTime();
        // on crée un objet Commande
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
       
     
        
        // on remplir les information pour une commande
        $commande->setUser($user)
                 ->setDateOfCommande(new DateTime("now"))
                 ->setAmount($total);
        // on persist le commande sans faire le flash car il nous faut les details de commande puis envoyer le tout en bdd
        $repoCom->save($commande);
       
        foreach ($dataBasket as  $value) {
            $commandeDetail = new CommandeDetail();

            $product = $value['product'];
            $quantity = $value['quantity'];
            $sousTotal = $value['sousTotal'];
         

            $commandeDetail->setQuantity($quantity)
                           ->setPrice($sousTotal)
                           ->setProduct($product)
                           ->setCommande($commande);
            $repoDet->save($commandeDetail);
            
        }
    
        
        // on envoit en bdd tout les objet persisté(commande et les commandeDetail)
        $manager->flush();
      
       
        // une fois le commande passé on supprime le panier
        $session->remove("basket");
        $this->addFlash('success', 'Votre commande à bien été enregistre !');
        
        return $this->redirectToRoute("app_basket");
       
    }


}
