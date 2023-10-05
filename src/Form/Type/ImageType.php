<?php

namespace App\Form\Type;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => '<i class="fa-solid fa-upload"></i>',
                'label_html' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'file-input__trigger',
                    'data-action' => 'input->image-preview#upload',
                    'data-image-preview-target' => "inputFile"
                ],
                'label_attr' => [
                    'class' => 'file-input__label'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (PNG, JPEG, JPG, GIF).',
                    ])
                ],
                'required' => false,
            ])
            ->add('filename', TextType::class, [
                'label' => false,
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'class' => 'd-none',
                    'data-image-preview-target' => 'inputFilename'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
