<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{

    public function __construct(private readonly VerifyEmailHelperInterface $verifyEmailHelper,private readonly MailerInterface $mailer)
    {
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
        $email->from($this->getParameter('from'));
        $email->to($user->getEmail());
        $email->htmlTemplate('app/pages/security/email/confirmation_email.html.twig');
        $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

        $this->mailer->send($email);
    }
}
