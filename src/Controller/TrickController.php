<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\Handler\TricksHandler;
use App\Form\Type\CommentType;
use App\Form\Type\TricksType;
use App\Repository\TricksRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TrickController extends AbstractController
{
    /**
     * @throws \JsonException
     */
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
                $response['html'][] = $this->renderView('app/pages/tricks/_card.html.twig', [
                    'trick' => $trick
                ]);
                $response['lastIndex'] = $trick['trickId'];
            }

            return $this->json($response);
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route(path: '/trick-manager/{slug}', name: 'add_tricks', defaults: ["slug" => null], methods: ['GET', 'POST'])]
    public function trickManager(?Trick $trick): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $param = [];
        if ($trick instanceof Trick){
            $form = $this->createForm(TricksType::class, $trick);
            $param = [
                'form' => $form->createView(),
                'trick' => $trick
            ];
        }

        return $this->render('app/pages/tricks/new_trick.html.twig', [
            'param' => $param
        ]);
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route(path: '/trick/detail', name: 'get_trick_detail_fetch', methods: ['POST'])]
    public function getTrickFetch(Request $request, TricksRepository $tricksRepository): Response
    {
        $slug = $request->request->get('pushModule');

        if ($slug) {
            $trick = $tricksRepository->findTrickBySlugWithMedia($slug);
            if (!$trick) {
                throw $this->createNotFoundException('Trick not found');
            }

            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);

            $html = $this->renderView('app/pages/tricks/_modal.html.twig', [
                'trick' => $trick,
                'form' => $form->createView(),
            ]);


            return $this->json($html);
        }

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/detail/{slug}', name: 'redirect_detail_to_home', methods: ['GET'])]
    public function redirectDetailToHome(): Response
    {
        return $this->redirectToRoute('home', []);
    }

    #[Route(path: '/trick/delete/{slug}', name: 'delete_trick', methods: ['GET'])]
    public function deleteTrick(Trick $trick, TricksRepository $tricksRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $tricksRepository->remove($trick, true);
        $this->addFlash('success', 'Le trick a bien été supprimé.');

        return $this->redirectToRoute('home', []);
    }

    #[Route(path: '/form/update/{id}', name: 'trick_form_fetch', defaults: ["id" => null], methods: ['POST'])]
    public function trickFormFetch(Request $request, TricksHandler $tricksHandler, TricksRepository $tricksRepository, ?int $id): ?Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED');

        $trick = $id ? $tricksRepository->find($id) : new Trick();
        $form = $this->createForm(TricksType::class, $trick);

        return $this->json($tricksHandler->handle($request, $form));
    }
}
