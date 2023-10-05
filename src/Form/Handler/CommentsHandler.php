<?php

namespace App\Form\Handler;

use App\Entity\Trick;
use App\Repository\TricksRepository;
use App\Traits\FlashTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;

class CommentsHandler
{
    use FlashTrait;

    private EntityManagerInterface $entity;
    private TricksRepository $tricksRepository;
    private Security $security;

    public function __construct(EntityManagerInterface $entity, TricksRepository $tricksRepository, Security $security)
    {
        $this->entity = $entity;
        $this->tricksRepository = $tricksRepository;
        $this->security = $security;
    }

    public function handle(Request $request, FormInterface $form, int $id): array
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick = $this->tricksRepository->findOneBy(['trick_id' => $id]);
            $currentUser = $this->security->getUser();

            $comment = $form->getData();
            $comment->setTrick($trick);
            $comment->setUser($currentUser);

            try {
                $this->entity->persist($comment);
                $this->entity->flush();
                return $this->renderMessage('success', 'Votre commentaire a bien été enregistré.');
            } catch (\Exception $e) {
            }
        }

        return $this->renderMessage('error', 'Une erreur est survenu lors de l\'enregistrement du formulaire.');
    }

}