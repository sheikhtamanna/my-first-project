<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Catagory;
use App\Form\ProductType;
use App\Form\CatagoryType;
use App\Repository\ProductRepository;
use App\Repository\CatagoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
   //----------------------------------Start Admin Catagory-------------------------------------------------------------------
   #[Route('/catagory_add', name: 'app_catagory_add')]
    public function add(Request $request, CatagoryRepository $repo, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
     $form = $this->createForm(CatagoryType::class);
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid())
     {
      $catagoriesForm = $form->get('name')->getData();
      $table = explode("," , $catagoriesForm);
      foreach($table as $name)
        {
            $catagory = new Catagory();
            $catagory->setName($name);
            $slug = $slugger->slug($name);
            $catagory->setSlug($slug);
            $repo->save($catagory);
        }
        $manager = $doctrine->getManager();
        $manager->flush();

        return $this->redirectToRoute("admin_app_catagories");
     }

        return $this->render("admin/catagory/form.html.twig",[
            'formCatagory' =>$form->createView()
        ]);
    }
    #[Route('/catagories', name: 'app_catagories')]
    public function showAll(CatagoryRepository $repo)
    {
        $catagories = $repo->findAll();

        return $this->render("admin/catagory/showAll.html.twig", [
                "catagories" => $catagories
        ]);
        
    }
    
    #[Route('/catagory_update_{slug}', name: 'app_catagory_update')]
    public function update($slug, Request $request, CatagoryRepository $repo, SluggerInterface $slugger)
    {
        $catagory = $repo->findOneBy(['slug' => $slug]);
        $form = $this->createForm(CatagoryType::class, $catagory);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $slug = $slugger->slug($catagory->getName());
            $catagory->setSlug($slug);
            $repo->save($catagory, 1);
            return $this->redirectToRoute('admin_app_catagories');
        }
        return $this->render('admin/catagory/form.html.twig',[
            'formCatagory' =>$form->createView()
        ]);
    }

    #[Route('/catagory_delete_{slug}', name: 'app_catagory_delete')]
    public function delete($slug,  CatagoryRepository $repo)
    {
        $catagory = $repo->findOneBy(['slug' => $slug]);
        $repo->remove($catagory,1);
        return $this->redirectToRoute('admin_app_catagories');
    }

   //----------------------------------End Admin Catagory---------------------------------------------------------------------

//------------------------------------Start Admin Product---------------------------------------------------------------
    #[Route('/product_add', name: 'app_product_add')]
    public function addProduct(Request $request, ProductRepository $repo, SluggerInterface $slugger)
    {
        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $file = $form->get('photoForm')->getData();
            if($file)
            {
                $fileName = $slugger->slug($product->getTitle()) .uniqid(). '.' .$file->guessExtension();
                try{
                 $file->move($this->getParameter('photos_product'), $fileName);
                }catch(FileException $e){
                 // uploading error
                }
                $product->setPhoto($fileName);
            }else{
                $this->addFlash('danger', 'Please fill in the photo field');
            }
           
           $repo->save($product,1);
           return $this->redirectToRoute('admin_app_products_administration');
            
        }
        return $this->render('admin/product/form.html.twig', [
            'formProduct' => $form->createView()
        ]);
    }
    #[Route('/products_administration', name: 'app_products_administration')]
    public function productsAdministration(ProductRepository $repo)
    {
        $products = $repo->findAll();
        return $this->render('admin/product/productsAdministration.html.twig', [
            'products' => $products
        ]);
    }
    #[Route('/product_details_{id<\d+>}', name: 'app_product_details')]
    public function ProductDetails($id, ProductRepository $repo )
    {
        $product = $repo->find($id);
        return $this->render('admin/product/productDetails.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/product_update_{id<\d+>}', name: 'app_product_update')]
    public function updateProduct($id, ProductRepository $repo, Request $request, SluggerInterface $slugger)
    {
        $product = $repo->find($id);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            
            $file = $form->get('photoForm')->getData();
            if($file)
            {
                $fileName = $slugger->slug($product->getTitle()) .uniqid(). '.' .$file->guessExtension();
                try{
                 $file->move($this->getParameter('photos_product'), $fileName);
                }catch(FileException $e){
                 // uploading error
                }
                $product->setPhoto($fileName);
            }
           $repo->save($product,1);
           return $this->redirectToRoute('admin_app_products_administration');
            
        }
        return $this->render('admin/product/form.html.twig', [
            'formProduct' => $form->createView()
        ]);
    }
    #[Route('/product_delete_{id<\d+>}', name: 'app_product_delete')]
    public function deleteProduct($id, ProductRepository $repo)
    {
        $product = $repo->find($id);
        $repo->remove($product, 1);
        return $this->redirectToRoute('admin_app_products_administration');

    }



   //-------------------------------------End Admin Product----------------------------------------------------------------
}
