<?php

namespace App\Form;

use App\Entity\card;
use App\Entity\Deck;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DeckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('closed', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('picture', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('card', EntityType::class, [
                'class' => card::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Deck::class,
        ]);
    }
}
