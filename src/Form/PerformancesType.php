<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class PerformancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('last_name', TextType::class, [
            'label' => 'NOM',
            'required' => true
        ])
        ->add('first_name', TextType::class, [
            'label' => 'PRENOM',
            'required' => true
        ])
        ->add('phoneNumber', TelType::class,[
            'label' => 'TEL',
            'required' =>true
        ])
        ->add('email', EmailType::class, [
            'label' => 'EMAIL',
            'required' => true
        ])
        ->add('event_type', ChoiceType::class, [
            'choices' => array(
                    'Séminaire d\'entreprise' => 'Séminaire d\'entreprise',
                    'Mariage' => 'Mariage',
                    'Anniversaire' => 'Anniversaire',
                    'Evénement public' => 'Evénement public',
                    'Autre' => 'Autre'
            ),
            'placeholder' => '-- Choisissez --',
            'label' => 'EVENEMENT',
            'expanded' => false,
            'required' => true,
        ])
            ->add('location_name', TextType::class, [
                'label' => 'NOM DU LIEU',
                'required' => true
            ])
            ->add('event_date', DateType::class, [
                'label' => 'DATE',
                'required' => true,
                'data' => new \DateTime(),
                'attr' => [
                    'placeholder' => 'JJ/MM/AAAA'
                ]
            ])
            ->add('hosts_number', IntegerType::class, [
                'label' => 'Nombre de convives',
                'required' => true
            ])
            ->add('start_event_time', TimeType::class, [
                'label' => 'Heure de début',
                'input' => 'string'
            ])
            ->add('end_event_time', TimeType::class, [
                'label' => 'Heure de fin',
                'input' => 'string'
            ])

            // Questions spécifiques

            ->add('celebration', ChoiceType::class, [
                'choices' => array(
                    'Oui' => 'Oui',
                    'Non' => 'Non'
                ),
                'label' => 'Une cérémonie se déroulera t-elle sur place ?',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('cocktail_location', ChoiceType::class, [
                'choices' => array(
                    'Oui' => 'Oui',
                    'Oui et attenant à l\'espace cérémonie (moins de 30m)' => 'Oui et attenant à l\'espace cérémonie (moins de 30m)',
                    'Oui et séparé de l\'espace cérémonie' => 'Oui et séparé de l\'espace cérémonie',
                    'Non' => 'Non'
                ),
                'label' => 'Un cocktail se déroulera t-il sur place ?',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('diner_dancefloor_separated', ChoiceType::class, [
                'choices' => array(
                    'Oui' => 'Oui',
                    'Non mais elle est attenante à l\'espace dîner' => 'Non mais elle est attenante à l\'espace dîner',
                    'Non, elle est complètement séparée de l\'espace dîner' => 'Non, elle est complètement séparée de l\'espace dîner'
                ),
                'label' => 'La piste de danse se trouve t-elle dans le même espace que le dîner ?',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('perf_comment', TextareaType::class, [
                'label' => 'Information(s) complémentaire(s) ou demande(s) spécifique(s) :',
                'attr' => array(
                    'rows' => 6,
                ),
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
