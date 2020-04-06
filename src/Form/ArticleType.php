<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "TITRE"
            ])
            ->add('category', EntityType::class, [
                'label' => "CATEGORIE",
                'class' => Category::class,
                'placeholder' => 'Choisissez une catégorie',
                'choice_label' => 'title'
            ])
            ->add('content', CKEditorType::class, [
                'label' => "DESCRIPTION",
                'attr' => array(
                    'rows' => 8
                )
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'label' => "IMAGE D'ILLUSTRATION",
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/*',
                            'mimeTypesMessage' => "Le fichier sélectionné doit être une image."     
                        ]
                    ])
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => "PRIX",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
