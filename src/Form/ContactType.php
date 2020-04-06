<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
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
            'label' => 'TEL *',
            'required' => true
        ])
        ->add('email', EmailType::class, [
            'label' => 'EMAIL',
            'required' => true
        ])
        ->add('subject', ChoiceType::class, [
            'label' => 'Choisissez un sujet à votre message',
            'choices' => array(
                    'Demande pour une prestation spécifique' => 'Demande pour une prestation spécifique',
                    'Demander une réservation (hors location)' => 'Demander une réservation (hors location)',
                    'Retour sur prestation' => 'Retour sur prestation',
                    'Autre' => 'Autre'
            ),
            'placeholder' => "Sujet de votre message",
            'expanded' => false,
            'required' => true,
        ])
        ->add('message', TextareaType::class, [
            'label' => 'MESSAGE :',
            'attr' => array(
                'rows' => 8
            ),
            'required' => true
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
