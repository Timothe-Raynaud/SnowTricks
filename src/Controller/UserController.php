<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegisterFormType;
use App\Model\UserManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/login", name="user_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="user_logout", methods={"GET"})
     */
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    /**
     * @Route("/inscription", name="user_register", methods={"POST", "GET"})
     */
    public function register(Request $request, UserManager $userManager): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $email = $form->get('email')->getData();
            $password = $form->get('plainPassword')->getData();

            $message = $userManager->registration($username, $email, $password, $user);

            if ($message === null){
                $this->addFlash('success', 'Un email de confirmation vent d\'être envoyé sur votre adresse email');
                return $this->redirectToRoute('home');
            }

            $this->addFlash('error', $message);
        }

        foreach ($form->getErrors(true, true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->render('pages/user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify", name="user_verify_email", methods={"GET"})
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');
        if ($userRepository->setIsValidated($id)){
            $this->addFlash('success', "Votre mail à bien été confirmer.");
        } else {
            $this->addFlash('error', "Une erreur est survenue lors de la validation de votre mail.");
        }

        return $this->redirectToRoute('home');
    }
}
