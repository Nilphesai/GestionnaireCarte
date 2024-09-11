<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Deck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('attribute', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('level', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('race', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('effect', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('att', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('def', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('link', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('scale', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('linkMarker', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('picture', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('typecard', TextType::class, [
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('decks', EntityType::class, [
                'class' => Deck::class,
                'choice_label' => 'id',
                'multiple' => true,
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
