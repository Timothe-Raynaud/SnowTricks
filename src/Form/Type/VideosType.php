<?php

namespace App\Form\Type;

use App\Entity\Video;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('video', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Set embed link',
                    'class' => 'custom-input',
                    'data-action'=> 'input->video-preview#preview'
                ],
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}