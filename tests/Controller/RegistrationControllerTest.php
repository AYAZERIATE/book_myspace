<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('registration/index.html.twig');
    }

    #[Route('/registration/register', name: 'app_registration_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $firstName = trim($request->request->get('first_name', ''));
        $lastName = trim($request->request->get('last_name', ''));
        $phone = trim($request->request->get('phone', ''));
        $email = trim($request->request->get('email', ''));
        $plainPassword = $request->request->get('password', '');

        if (
            $firstName === '' ||
            $lastName === '' ||
            $phone === '' ||
            $email === '' ||
            $plainPassword === ''
        ) {
            $this->addFlash('error', 'Please fill in all required fields.');

            return $this->redirectToRoute('app_registration');
        }

        // Vérification de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'Please enter a valid email address.');

            return $this->redirectToRoute('app_registration');
        }

        if (strlen($plainPassword) < 8) {
            $this->addFlash('error', 'Password must contain at least 8 characters.');

            return $this->redirectToRoute('app_registration');
        }

        $existingUser = $entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($existingUser !== null) {
            $this->addFlash('error', 'An account with this email already exists.');

            return $this->redirectToRoute('app_registration');
        }

        // Création du User
        $user = new User();

        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPhone($phone);
        $user->setEmail($email);

        // Password hashé avant de l'enregistrer
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );

        $user->setPassword($hashedPassword);

        // Données automatiques
        $user->setProfileImage(null);
        $user->setCreatedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        // Enregistrement dans MySQL
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Your account has been created successfully.');

        return $this->redirectToRoute('app_login');
    }
}