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

class TricksHandler
{
    use FlashTrait;

    private EntityManagerInterface $entity;
    private ParameterBagInterface $parameter;
    private SessionInterface $session;
    private TricksRepository $tricksRepository;

    public function __construct(EntityManagerInterface $entity, ParameterBagInterface $parameter, SessionInterface $session, TricksRepository $tricksRepository)
    {
        $this->parameter = $parameter;
        $this->entity = $entity;
        $this->session = $session;
        $this->tricksRepository = $tricksRepository;
    }

    public function handle(Request $request, FormInterface $form): array
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form = $this->imageHandle($form);

            $trick = $form->getData();
            $trick->setSlug();

            if (!$this->entity->contains($trick)){
                if ($this->tricksRepository->findOneBy(['slug' => $trick->getSlug()]) instanceof Trick){
                    return $this->renderMessage('error', 'Un trick avec ce nom existe déjà.');
                }
                $this->entity->persist($trick);
            }

            try {
                $this->entity->flush();
                return $this->renderMessage('success', 'Le tricks à bien été enregistré.');
            } catch (\Exception $e) {
            }
        }

        return $this->renderMessage('error', "Une erreur est survenu lors de l\'enregistrement du formulaire.");
    }

    public function imageHandle(FormInterface $form): ?FormInterface
    {
        foreach ($form['images'] as $imageForm) {
            // Test if filename exist. If is case there is no file to download.
            if ($imageForm->has('filename') && ($imageForm->get('filename')->getData() !== null)){
                continue;
            }

            // If file exist, move to own directory.
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