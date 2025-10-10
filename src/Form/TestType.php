<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('field_name', RangeType::class, [
            //     'label' => 'rang',
            //     'attr' => [
            //         'min' => 5,
            //         'max' => 50
            //     ],
            // ])
            ->add('min', TextType::class, [
                'label' => 'prix minimum',
                'required'   => false,


            ])
            ->add('max', TextType::class, [
                'label' => 'prix maximum',
                'required'   => false,

            ])
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
            'attr' => [
                    'placeholder' => 'Votre recherche',
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true
            ])
            ->add('filter', ChoiceType::class, [
                'choices' => [
                    'Trier par prix ' => [
                        'Prix croissant' => 'prix_ASC',
                        'Prix décroissant' => 'prix_DESC',
                    ],
                    'Trier par nom' => [
                        'Nome croissant' => 'nom_ASC',
                        'Nome décroissant' => 'nom_DESC',
                    ],
                ],
            // 'mapped' => false
            ])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Summettre'
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
