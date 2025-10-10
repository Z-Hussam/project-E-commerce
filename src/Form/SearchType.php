<?php

namespace App\Form;

use App\Classes\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                // 'label' => 'Tapez votre recherch',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre recherche',
                    'class' => ' form-sm'
                ]
            ])
            ->add('filter', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Trier par prix ' => [

                        'Prix croissant' => 'prix_ASC',
                        'Prix décroissant' => 'prix_DESC',
                    ],
                    'Trier par nom' => [
                        'Nome croissant' => 'nom_ASC',
                        'Nome décroissant' => 'nom_DESC',
                    ],
                   
                ], 'attr'=>[
                        'class'=>' input-sm'
                    ]

            ])
            ->add('min', TextType::class, [
                // 'label' => 'prix minimum €',
                'label' => false,
                'required'   => false,
                'attr' => [
                    'placeholder' => 'Minimum prix',
                    'classe'=>' input-sm'
                ]
            ])
            ->add('max', TextType::class, [
                // 'label' => 'prix maximum €',
                'label' => false,
                'required'   => false,
                'attr' => [
                    'placeholder' => 'Maximum prix',
                    'classe'=>' input-sm'
                ]
            ])
          
            ->add('categories', EntityType::class, [
                'label' => false,
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Recherchez',
                'attr' => [
                    'class' => 'btn-block btn-secondary  text-white'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
