<?php

namespace App\Twig\Components;

use App\Entity\Trick;
use App\Form\Type\TricksType;
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
    public ?Trick $trick = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(
            TricksType::class,
            $this->trick
        );
    }

    #[LiveAction]
    public function addImage(): void
    {
        $this->formValues['images'][] = [];
    }

    #[LiveAction]
    public function removeImage(#[LiveArg] int $index): void
    {
        unset($this->formValues['images'][$index]);
    }
}
