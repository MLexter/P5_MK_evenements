<?php

namespace App\Form;

use App\Entity\About;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AboutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content1', TextareaType::class, [
                'label' => 'Description 1',
                'required' => true,
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('content2', TextareaType::class, [
                'label' => 'Description 2',
                'required' => true,
                'attr' => [
                    'rows' => 10
                ]
            ])
            ->add('content3', TextareaType::class, [
                'label' => 'Description 3',
                'required' => true,
                'attr' => [
                    'rows' => 10
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => About::class,
        ]);
    }
}
