<?php

namespace App\Form\Type;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
            'label' => 'Upload',
            'mapped' => false,
            'attr' => [
                'class' => 'file-input__trigger',
                'data-action'=> 'input->image-preview#upload',
            ],
            'label_attr' => [
                'class' => 'file-input__label'
            ],
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        '.jpg',
                        '.png'
                    ],
                    'mimeTypesMessage' => 'Please upload a valid image',
                ])
            ],
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}