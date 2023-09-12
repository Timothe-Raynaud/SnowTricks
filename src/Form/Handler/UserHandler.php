<?php

namespace App\Form\Handler;

use App\Traits\FlashTrait;
use App\Repository\UserRepository;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserHandler
{
    use FlashTrait;

    private EntityManagerInterface $entity;
    private UserRepository $userRepository;
    private MailerController $mailerController;
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(EntityManagerInterface $entity, UserRepository $userRepository, MailerController $mailerController, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entity = $entity;
        $this->userRepository = $userRepository;
        $this->mailerController = $mailerController;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function handle(Request $request, FormInterface $form): ?array
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            if ($this->userRepository->findOneBy(['username' => $user->getUsername()])){
                return $this->renderMessage("error", "Ce nom d'utilisateur est déjà prit");
            }

            if ($this->userRepository->findOneBy(['email' => $user->getEmail()])){
                return $this->renderMessage("error", "Un compte est déjà liée à cet email.");
            }
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );

            try {
                $this->entity->persist($user);
                $this->entity->flush();
                $this->mailerController->sendConfirmationMailUser($user);

                return $this->renderMessage('success', 'Un email de confirmation vent d\'être envoyé sur votre adresse email');
            } catch (\Exception $e) {
                dd($e);
            }
        }

        return null;
    }

}