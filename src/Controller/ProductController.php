<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends AbstractController
{
    #[Route('/admin/back-office', name: 'product_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $product->setImage($newFilename);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_new');
        }

        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/back_office.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }

    #[Route('/back-office/{id}/edit', name: 'product_edit')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $product->setImage($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('product_new');
        }

        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/back_office.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }

    #[Route('/admin/product/{id}/delete', name: 'product_delete')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_new');
    }

    #[Route('/products', name: 'product_list')]
    public function list(Request $request, EntityManagerInterface $entityManager): Response
    {
        $priceRange = $request->query->get('price_range', 'all');
        $products = $entityManager->getRepository(Product::class)->findAll();

        if ($priceRange != 'all') {
            $priceLimits = explode('-', $priceRange);
            $products = array_filter($products, function($product) use ($priceLimits) {
                return $product->getPrice() >= $priceLimits[0] && $product->getPrice() <= $priceLimits[1];
            });
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'selectedPriceRange' => $priceRange,
        ]);
    }

    #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
public function addToCart($id, Request $request, SessionInterface $session, EntityManagerInterface $entityManager): Response
{
    $product = $entityManager->getRepository(Product::class)->find($id);
    $size = $request->request->get('size');

    if (!$product || !$size) {
        return $this->redirectToRoute('product_show', ['id' => $id]);
    }


    $stockField = 'stock' . strtoupper($size);
    $availableStock = $product->{'get' . ucfirst($stockField)}();

    if ($availableStock <= 0) {
        $this->addFlash('error', 'Désolé, cette taille n\'est plus disponible.');
        return $this->redirectToRoute('product_show', ['id' => $id]);
    }
    

    $cart = $session->get('cart', []);
    $cartItem = [
        'product' => $product,
        'size' => $size,
        'quantity' => 1
    ];

    if (isset($cart[$id][$size])) {
        if ($cart[$id][$size]['quantity'] + 1 > $availableStock) {
            $this->addFlash('error', 'Désolé, vous ne pouvez pas ajouter plus de cette taille au panier.');
            return $this->redirectToRoute('product_show', ['id' => $id]);
        }
        $cart[$id][$size]['quantity']++;
    } else {
        $cart[$id][$size] = $cartItem;
    }

    $session->set('cart', $cart);

    return $this->redirectToRoute('cart_index');
}

}
