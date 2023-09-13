<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    private VerifyEmailHelperInterface $verifyEmailHelper;
    private MailerInterface $mailer;

    public function __construct(VerifyEmailHelperInterface $verifyEmailHelper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
    }

    #[Route('/confirmation', name: 'email_confirmation_user', methods: ['GET', 'POST'])]
    public function sendConfirmationMailUser(User $user): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'user_verify_email',
            $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $email = new TemplatedEmail();
        $email->from('send@example.com');
        $email->to($user->getEmail());
        $email->htmlTemplate('app/pages/security/email/confirmation_email.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($email);
    }
}