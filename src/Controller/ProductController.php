<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;



class ProductController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserInterface $currentUser;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->currentUser = $security->getUser();
    }

    #[Route('/', name: 'app_product')]
    public function index(): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('home/home.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/category/{category}', name: 'app_product_toys')]
    public function toysProduct($category): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByCategory($category);

        return $this->render('home/home.html.twig', [
            'products' => $products,
        ]);
    }
}

