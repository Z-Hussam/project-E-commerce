<?php

namespace App\Form;

use App\Entity\Addresse;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Choisissez votre adresse de livraison',
                'required' => true,
                'class' => Addresse::class,
                'expanded' => true,
                'multiple'=>false,
                'choices' => $options['addresses'],
                'label_html' => true,
                 'attr' => [
                'class' => '  w-100'
                ]

            ])
            ->add('carriers', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                'class' => Carrier::class,
                'expanded' => true,
                'multiple'=>false,
                // 'choices' => $options['addresses'],
                'label_html' => true,
                 'attr' => [
                'class' => 'w-100'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => ' Confirmer et Continuer ',
                'attr' => [
                'class' => 'btn text-white bg-dark btn-sm py-3  btn-block  w-100'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null
        ]);
    }
}
