<?php

namespace App\Form;

use App\Entity\Podcast;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PodcastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('fechaSubida', DateTimeType::class, [
                'label' => 'Fecha de Subida',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('autor', TextType::class, [
                'label' => 'Autor',
                'attr' => ['class' => 'form-control'],
                'disabled' => true,
            ])
            
            ->add('audio', FileType::class, [
                'label' => 'Archivo de audio',
                'attr' => ['class' => 'form-control-file'],
                'required' => false,
                "data_class" => null,

                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'audio/mpeg',
                            'audio/mp3',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube un archivo de audio válido (MP3).',
                    ]),
                ],
            ])
          
            ->add('imagen', FileType::class, [
                'label' => 'Imagen',
                'attr' => ['class' => 'form-control-file'],
                'required' => false,
                "data_class" => null,

                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen válida (JPEG, PNG).',
                    ]),
                ],
            ]);
            
        // Añade los otros campos de tu entidad aquí
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Podcast::class,
        ]);
    }
}
