<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\Handler\TricksHandler;
use App\Form\Type\TricksType;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    #[Route(path: '/get_tricks', name: 'get_tricks', methods: ['POST'])]
    public function getTricks(Request $request, TricksRepository $tricksRepository): Response
    {

        $requestContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!empty($requestContent['elementNumber'])) {
            $limit = $requestContent['elementNumber'];

            $startingId = null;
            if (!empty($requestContent['startingId'])) {
                $startingId = $requestContent['startingId'];
            }

            $tricks = $tricksRepository->getTricksWithType($limit, $startingId);

            $response = [];
            foreach ($tricks as $trick) {
                $response['html'][] = $this->renderView('pages/tricks/_card.html.twig', [
                    'trick' => $trick
                ]);
                $response['lastIndex'] = $trick['trickId'];
            }

            return $this->json($response);
        }

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/add-tricks', name: 'add_tricks', methods: ['GET', 'POST'])]
    public function addTricks(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_login');
        }

        $trick = new Trick();
        $form = $this->createForm(TricksType::class, $trick);

        return $this->render('pages/tricks/new_trick.html.twig', [
            'form' => $form,
            'trick' => $trick
        ]);
    }

    #[Route(path: '/trick/detail', name: 'get_trick_detail_fetch', methods: ['POST'])]
    public function getTrickFetch(Request $request, TricksRepository $tricksRepository, CommentsRepository $commentsRepository): Response
    {
        $slug = $request->request->get('pushModule');

        if ($slug) {
            $trick = $tricksRepository->findTrickBySlugWithMedia($slug);
            if (!$trick) {
                throw $this->createNotFoundException('Trick not found');
            }

            $html = $this->renderView('pages/tricks/_modal.html.twig', [
                'trick' => $trick,
            ]);

            return $this->json($html);
        }

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/detail/{slug}', name: 'redirect_detail_to_home', methods: ['GET'])]
    public function redirectDetailToHome(string $slug): Response
    {
        return $this->redirectToRoute('home', []);
    }

    #[Route(path: '/trick/delete/{slug}', name: 'delete_trick', methods: ['GET'])]
    public function deleteTrick(string $slug, TricksRepository $tricksRepository, CommentsRepository $commentsRepository): Response
    {
        $trick = $tricksRepository->findOneBy(['slug' => $slug]);
        if ($trick === null) {
            $this->addFlash('error', 'Un problème est survenue lors de la suppression du trick.');
            return $this->redirectToRoute('home', []);
        }

        $comments = $commentsRepository->findBy(['trick' => $trick]);

        foreach ($comments as $comment) {
            $commentsRepository->remove($comment, true);
        }

        $tricksRepository->remove($trick, true);

        $this->addFlash('success', 'Le trick a bien été supprimé.');

        return $this->redirectToRoute('home', []);
    }

    #[Route(path: '/updated/trick', name: 'get_update_trick_update_html', methods: ['GET', 'POST'])]
    public function getUpdateTrickFetch(Request $request, TricksRepository $tricksRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('user_login');
        }

        $slug = $request->request->get('pushModule');

        if ($slug) {
            $trick = $tricksRepository->findTrickBySlugWithMedia($slug);

            if (!$trick) {
                throw $this->createNotFoundException('Trick not found');
            }

            $form = $this->createForm(TricksType::class, $trick);

            $html = $this->renderView('pages/tricks/_update.html.twig', [
                'form' => $form->createView(),
                'trick' => $trick
            ]);

            return $this->json($html);
        }

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/form/update', name: 'trick_form_fetch', methods: ['POST'])]
    public function trickFormFetch(Request $request, TricksHandler $tricksHandler): ?Response
    {
        $trick = new Trick();
        $form = $this->createForm(TricksType::class, $trick);
        return $this->json($tricksHandler->handle($request, $form));
    }
}
