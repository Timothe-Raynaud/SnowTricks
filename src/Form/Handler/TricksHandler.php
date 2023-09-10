<?php

namespace App\Form\Handler;

use App\Traits\FlashTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TricksHandler
{
    use FlashTrait;

    private EntityManagerInterface $entity;
    private ParameterBagInterface $parameter;
    private SessionInterface $session;

    public function __construct(EntityManagerInterface $entity, ParameterBagInterface $parameter, SessionInterface $session)
    {
        $this->parameter = $parameter;
        $this->entity = $entity;
        $this->session = $session;
    }

    public function handle(Request $request, FormInterface $form): array
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($form === null) {
                return $this->renderMessage('error', "Le formulaire semble vide");
            }

            $form = $this->imageHandle($form);

            $trick = $form->getData();
            $trick->setSlug();

            try {
                $this->entity->persist($trick);
                $this->entity->flush();
            } catch (\Exception) {
                return $this->renderMessage('error', "Une erreur est survenu lors de l\'enregistrement du formulaire.");
            }
            return $this->renderMessage('success', 'Le tricks à bien été enregistré.');
        }

        return $this->renderMessage('error', "Une erreur est survenu lors de l\'enregistrement du formulaire.");
    }

    public function imageHandle(FormInterface $form): ?FormInterface
    {
        foreach ($form['images'] as $imageForm) {
            if ($imageForm->has('file') && $fileData = $imageForm->get('file')->getData()) {

                $originalFilename = pathinfo($fileData->getClientOriginalName(), PATHINFO_FILENAME);

                $filename = $originalFilename . '-' . uniqid('', true) . '.' . $fileData->guessExtension();

                try {
                    $fileData->move(
                        $this->parameter->get('trick_image_directory'),
                        $filename
                    );
                } catch (FileException $e) {
                    $this->session->getFlashBag()->add('error', 'Une erreur est survenu à cause d\'une image');
                    return null;
                }
                $imageObject = $imageForm->getData();
                $imageObject->setFilename($filename);
            }
        }

        return $form;
    }
}