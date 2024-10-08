<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findBy([], ['id' => 'DESC'], 3);

        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }
}
