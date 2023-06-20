<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => 'Buscar podcasts',
                'attr' => ['placeholder' => 'Buscar podcasts'],
            ]);
    }
}
