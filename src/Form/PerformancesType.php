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
                    'Mariage' => 'Mariage',
                    'Anniversaire' => 'Anniversaire',
                    'Evénement privé' => 'Evénement privé',
                    'Evénement public' => 'Evénement public',
                    'Evénement d\'entreprise' => 'Evénement d\'entreprise',
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
                'label' => 'Nombre d\'invités',
                'required' => true
            ])
            ->add('end_event_time', TimeType::class, [
                'label' => 'Heure de fin de l\'événement',
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
                    'Non' => 'Non'
                ),
                'label' => 'Un cocktail se déroulera t-il sur place ?',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('diner_dancefloor_separated', ChoiceType::class, [
                'choices' => array(
                    'Oui' => 'Oui',
                    'Non' => 'Non'
                ),
                'label' => 'L\'espace de réception et la piste de danse sont-ils dans la même salle ?',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('close_distant_spaces', ChoiceType::class, [
                'choices' => array(
                    'Attenants' => 'Attenants',
                    'Séparés' => 'Séparés',
                    'Non concerné' => 'Non concerné'
                ),
                'label' => 'Les 2 espaces sont-ils attenants ou complètement séparés ?',
                'multiple' => false,
                'expanded' => true
            ])
            ->add('perf_comment', TextareaType::class, [
                'label' => 'Pour pouvoir vous apporter une solution sur mesure, décrivez-nous l\'événement en quelques lignes :',
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
