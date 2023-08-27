<?php

namespace App\Controller;

use App\Model\CommentManager;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comments", name="comments_")
 */
class CommentsController extends AbstractController
{
    /**
     * @Route("/form", name="form", methods={"POST"})
     */
    public function index(Request $request, CommentManager $commentManager): Response
    {
        $form = $request->request->all();
        $result = $commentManager->addComment($form, $this->getUser());

        return $this->json($result);
    }

    /**
     * @Route("/get-comments-fetch/{slug}", name="get_comments-fetch", methods={"POST"})
     */
    public function getCommentsFetch(String $slug, Request $request, CommentsRepository $commentsRepository, TricksRepository $tricksRepository): Response
    {
        $trick = $tricksRepository->findOneBy(['slug' => $slug]);

        if (!empty($request->request->get('loaderModule'))){
            $comments = $commentsRepository->findBy(['trick' => $trick], ['comments_id' => 'DESC']);

            $html = [];
            foreach($comments as $comment){
                $html[] = $this->renderView('pages/tricks/_comment.html.twig', [
                    'comment' => $comment
                ]);
            }

            return $this->json($html);
        }

        return $this->redirectToRoute('home');
    }
}
