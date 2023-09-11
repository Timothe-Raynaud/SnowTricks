<?php

namespace App\Controller;

use App\Model\CommentManager;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comments', name: 'comments_')]
class CommentController extends AbstractController
{
    #[Route('/form', name: 'form', methods: ['POST'])]
    public function addComment(Request $request, CommentManager $commentManager): Response
    {
        $form = $request->request->all();
        $result = $commentManager->addComment($form, $this->getUser());

        return $this->json($result);
    }

    #[Route('/get-comments-fetch/{slug}', name: 'get_comments-fetch', methods: ['POST'])]
    public function getCommentsFetch(string $slug, Request $request, CommentsRepository $commentsRepository, TricksRepository $tricksRepository): Response
    {
        $trick = $tricksRepository->findOneBy(['slug' => $slug]);
        if ($trick === null){
            throw $this->createNotFoundException('Une erreur est survenue');
        }

        $requestContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!empty($requestContent['elementNumber'])) {
            $limit = $requestContent['elementNumber'];

            $startingId = null;
            if (!empty($requestContent['startingId'])) {
                $startingId = $requestContent['startingId'];
            }

            $comments = $commentsRepository->getCommentsByTrick($trick->getTrickId(), $limit, $startingId);

            $response = [];
            foreach($comments as $comment){
                $response['html'][] = $this->renderView('pages/tricks/_comment.html.twig', [
                    'comment' => $comment
                ]);
                $response['lastIndex'] = $comment['commentId'];
            }

            return $this->json($response);
        }

        return $this->redirectToRoute('home');
    }
}