<?php

namespace App\Model;

use App\Entity\Comments;
use App\Entity\Tricks;
use App\Entity\User;
use App\Repository\CommentsRepository;
use App\Repository\TricksRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Trait\FlashTrait;

class CommentManager
{
    use FlashTrait;

    private EntityManagerInterface $entityManager;
    private TricksRepository $tricksRepository;

    public function __construct(EntityManagerInterface $entityManager, TricksRepository $tricksRepository)
    {
        $this->entityManager = $entityManager;
        $this->tricksRepository = $tricksRepository;
    }

    public function addComment(array $form, ?User $user) : array
    {
        if (!$user){
            return $this->renderMessage('error', "Vous devez être connecté pour laisser un commentaire");
        }
        $content = $form['content-comment-form'];
        if (!$content){
            return $this->renderMessage('error', "Du contenue est nécessaire pour laisser un commentaire");
        }
        $slug = $form['trick'];
        if ($slug){
           $trick = $this->tricksRepository->findOneBy(['slug' => $slug]);
           if ($trick instanceof Tricks){
               $comments = new Comments();

               $comments->setUser($user);
               $comments->setContent($content);
               $comments->setTrick($trick);
               $comments->setCreatedAt();
               $comments->setUpdatedAt();

               $this->entityManager->persist($comments);
               $this->entityManager->flush($comments);

               return $this->renderMessage('success', "Votre commentaire à été laissé");
           }
        }

        return $this->renderMessage('error', "Une erreur s'est produite");
    }
}