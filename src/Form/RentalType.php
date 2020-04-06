<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'NOM *'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'PRENOM *'
            ])
            ->add('phoneNumber', TelType::class,[
                'label' => 'TEL *'
            ])
            ->add('email', EmailType:: class, [
                'label' => 'EMAIL *'
            ])
            ->add('customerMessage', TextareaType:: class, [
                'label' => 'VOTRE MESSAGE',
                'required' => false,
                'attr' => array(
                    'rows' => 8
                )
            ])
            ->add('rentalDate', TextType::class, [
                'label' => 'DATE DE LOCATION *',
                'attr' => [
                    'placeholder' => 'JJ/MM/AAAA'
                ]
            ])
            ->add('NbOfDays', IntegerType::class, [
                'label' => 'NOMBRE DE JOURS DE LOCATION *'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'method' => 'POST',
            'csrf_protection' => false
        ]);
    }
}
