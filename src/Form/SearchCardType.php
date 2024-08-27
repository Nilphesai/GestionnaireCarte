<?php

namespace App\Form;

use App\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('attribute', TextType::class,[
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('level', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('race', TextType::class,[
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('effect', TextType::class,[
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('att', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('def', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('link', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('scale', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('linkMarker', TextType::class,[
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('Chercher', SubmitType::class, [
                'attr' => [
                        'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
