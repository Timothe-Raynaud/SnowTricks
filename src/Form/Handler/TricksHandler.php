<?php

namespace App\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TricksHandler
{
    private EntityManagerInterface $entity;
    private ParameterBagInterface $parameter;
    private SessionInterface $session;

    public function __construct(EntityManagerInterface $entity, ParameterBagInterface $parameter, SessionInterface $session)
    {
        $this->parameter = $parameter;
        $this->entity = $entity;
        $this->session = $session;
    }

    public function handle(Request $request, FormInterface $form ): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form = $this->imageHandle($form);
            if ($form === null){
                return false;
            }

            $trick = $form->getData();
            $trick->setSlug();

            dd($trick, $this->entity->persist($trick), $this->entity->flush());
            try{
                $this->entity->persist($trick);
                $this->entity->flush();
            } catch (\Exception){
                $this->session->getFlashBag()->add('error', 'Une erreur est survenu lors de l\'enregistrement du formulaire.');
                return false;
            }
            return true;
        }

        return false;
    }

    public function imageHandle(FormInterface $form) : ?FormInterface
    {
        foreach ($form['images'] as $imageForm) {
            if ($imageForm->has('file') && $fileData = $imageForm->get('file')->getData()) {
                $originalFilename = pathinfo($fileData->getClientOriginalName(), PATHINFO_FILENAME);

                $filename = $originalFilename.'-'.uniqid('', true).'.'.$fileData->guessExtension();

                try {
                    $fileData->move(
                        $this->parameter->get('image_directory'),
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