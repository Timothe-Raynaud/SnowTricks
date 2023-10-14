<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Handler\UserHandler;
use App\Form\Type\RegisterType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route(path: '/login', name: 'user_login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error){
            $this->addFlash('error', 'Login ou mot de passe incorrect.');
        }
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('app/pages/security/user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'user_logout', methods: ['GET'])]
    public function logout(): void
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route(path: '/inscription', name: 'user_register', methods: ['POST', 'GET'])]
    public function register(Request $request, UserHandler $userHandler): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $result = $userHandler->handle($request, $form);

        if ($result){
            if ($result['type'] === 'success'){
                $this->addFlash($result['type'], $result['message']);
                return $this->redirectToRoute('home');
            }

            $this->addFlash($result['type'], $result['message']);
        }

        return $this->render('app/pages/security/user/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route(path: '/verify', name: 'user_verify_email', methods: ['GET'])]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');
        $user = $userRepository->findOneBy(['user_id' => $id]);
        if ($user instanceof User){
            $user->setIsVerified(true);
            $userRepository->update($user);
            $this->addFlash('success', "Votre mail à bien été confirmer.");

            return $this->redirectToRoute('user_login');
        }

        $this->addFlash('error', "Une erreur est survenue lors de la validation de votre mail.");

        return $this->redirectToRoute('home');
    }
}
