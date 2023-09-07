<?php

namespace App\Twig\Components;

use App\Entity\Tricks;
use App\Form\Type\TricksFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;


#[AsLiveComponent]
class TricksForm extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp]
    public ?Tricks $trick = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            TricksFormType::class,
            $this->trick
        );
    }

    #[LiveAction]
    public function addImage(): void
    {
        echo 'test';
        $this->formValues['images'][] = [];
    }

    #[LiveAction]
    public function removeImage(#[LiveArg] int $index): void
    {
        unset($this->formValues['images'][$index]);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $entityManager) : Response
    {
        $this->submitForm();

        $trick = $this->getForm()->getData();

        $entityManager->persist($trick);
        $entityManager->flush();

        $this->addFlash('success', 'Post saved!');

        return $this->redirectToRoute('home', []);
    }
}
