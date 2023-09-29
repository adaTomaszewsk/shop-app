<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
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


    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('home/home.html.twig', [
              'products' => $products,
        ]); 
    }
}
