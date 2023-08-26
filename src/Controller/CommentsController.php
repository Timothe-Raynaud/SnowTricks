<?php

namespace App\Controller;

use App\Model\CommentManager;
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
}
