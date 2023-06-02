<?php

namespace App\Model;

use App\Controller\MailerController;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;
    private EntityManagerInterface $entityManager;
    private MailerController $mailerController;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository, MailerController $mailerController)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->userRepository = $userRepository;
        $this->mailerController = $mailerController;
    }


    /**
     * @throws TransportExceptionInterface
     */
    public function registration(string $username, string $email, string $password, User $user) : ?string
    {
        if ($this->userRepository->findOneBy(['username' => $username])){
            return "Ce nom d'utilisateur est déjà prit";
        }

        if ($this->userRepository->findOneBy(['email' => $email])){
            return "Un compte est déjà liée à cet email.";
        }

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->mailerController->sendConfirmationMailUser($user);

        return null;
    }
}