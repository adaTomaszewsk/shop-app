<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegisterType;
use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/registration', name: 'app_registration')]
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request): Response
    {

        $user= new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        dump($form);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $user->getPassword(),
            );
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('authentication/registration.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/login', name:'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername= $authenticationUtils->getLastUsername();



        return $this->render('authentication/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
