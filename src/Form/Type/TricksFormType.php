<?php

namespace App\Form\Type;

use App\Entity\Tricks;
use App\Entity\TypesTricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class TricksFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'name'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'custom-input textarea',
                    'placeholder' => 'Description',
                    'rows' => '8'
                ],
            ])
            ->add('type', EntityType::class, [
                'class' => TypesTricks::class,
                'choice_label' => 'name',
                'label' => false,
                'attr' => [
                    'class' => 'custom-input'
                ],
            ])
            ->add('images', LiveCollectionType::class, [
                'entry_type' => ImagesType::class,
            ])
            ->add('videos', LiveCollectionType::class, [
                'entry_type' => VideosType::class,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
