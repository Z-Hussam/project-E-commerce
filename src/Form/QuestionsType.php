<?php

namespace App\Form;

use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question',TextType::class,[
                'label'=>'Votre question',
                'constraints'=>[
                    new Length([
                        'min'=>2,
                        'max'=>150
                    ])
                ],
                'attr'=>[
                    'placeholder'=>'Mercie de saisir votre question',
                    'class'=>'form-control'
                ]
                ])
                ->add('submit',SubmitType::class,[
                    'label'=>'Validez',
                    'attr'=>[
                        'class'=>'btn btn-secondary text-white w-100'
                        ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
