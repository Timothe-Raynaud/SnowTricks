<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\Handler\CommentsHandler;
use App\Form\Type\CommentType;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comments', name: 'comments_')]
class CommentController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('/form/{id}', name: 'form', methods: ['POST'])]
    public function addComment(Request $request, int $id, CommentsHandler $commentsHandler): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        return $this->json($commentsHandler->handle($request, $form, $id));
    }

    /**
     * @throws \JsonException
     */
    #[Route('/get-comments-fetch/{slug}', name: 'get_comments-fetch', methods: ['POST'])]
    public function getCommentsFetch(Trick $trick, Request $request, CommentsRepository $commentsRepository, TricksRepository $tricksRepository): Response
    {
        $requestContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!empty($requestContent['elementNumber'])) {
            $limit = $requestContent['elementNumber'];

            $startingId = null;
            if (!empty($requestContent['startingId'])) {
                $startingId = $requestContent['startingId'];
            }

            $comments = $commentsRepository->getCommentsByTrick($trick->getTrickId(), $limit, $startingId);

            $response = [];
            foreach ($comments as $comment) {
                $response['html'][] = $this->renderView('app/pages/tricks/_comment.html.twig', [
                    'comment' => $comment
                ]);
                $response['lastIndex'] = $comment['comment_id'];
            }

            return $this->json($response);
        }

        return $this->redirectToRoute('home');
    }
}
