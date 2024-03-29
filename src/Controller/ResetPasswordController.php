<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordFormType;
use App\Form\Type\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('', name: 'app_forgot_password_request', methods: ['GET', 'POST'])]
    public function request(Request $request, MailerInterface $mailer, EntityManagerInterface $entityManager, ResetPasswordHelperInterface $resetPasswordHelper): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer,
                $entityManager,
                $resetPasswordHelper
            );
        }

        return $this->render('app/pages/security/user/reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    #[Route('/check-email', name: 'app_check_email', methods: ['GET', 'POST'])]
    public function checkEmail(): Response
    {
        return $this->render('app/pages/home.html.twig');
    }

    #[Route('/reset/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, ResetPasswordHelperInterface $resetPasswordHelper, TranslatorInterface $translator, EntityManagerInterface $entityManager, string $token = null): Response
    {
        if ($token) {
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // The token is valid; allow the user to change their password.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $resetPasswordHelper->removeResetRequest($token);

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );

            $user->setPassword($encodedPassword);
            $entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            $this->addFlash('success', 'Votre mot de passe vient d\'être réinitialisé.');
            return $this->redirectToRoute('home');
        }

        return $this->render('app/pages/security/user/reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, EntityManagerInterface $entityManager,ResetPasswordHelperInterface $resetPasswordHelper): RedirectResponse
    {
        $user = $entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);
        // Do not reveal whether a user account was found or not.
        if (!$user) {
            $this->addFlash('error', 'Le mail semble incorrect.');
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $resetPasswordHelper->generateResetToken($user);
            $email = (new TemplatedEmail())
                ->from(new Address('contact@test.com', 'Snowtricks reset password'))
                ->to($user->getEmail())
                ->subject('Nouveau mot de passe')
                ->htmlTemplate('app/pages/security/email/reset_password_email.html.twig')
                ->context([
                    'resetToken' => $resetToken,
                ]);

            $mailer->send($email);

            $this->setTokenObjectInSession($resetToken);
            $this->addFlash('success', 'Un mail de réinitialisation vient de vous être envoyé.');
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());
        }

        return $this->redirectToRoute('app_check_email');
    }
}
