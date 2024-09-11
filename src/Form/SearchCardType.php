<?php

namespace App\Form;

use App\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;

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
            ->add('typecard', ChoiceType::class,[
                'choices' => [
                    'MONSTER' => 'MONSTER',
                    'SPELL' => 'Spell Card',
                    'TRAP' => 'Trap Card',
                ],
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('attribute', ChoiceType::class,[
                'choices' => [
                    'LIGHT' => 'LIGHT',
                    'DARK' => 'DARK',
                    'WIND' => 'WIND',
                    'EARTH' => 'EARTH',
                    'FIRE' => 'FIRE',
                    'WATER' => 'WATER',
                    'DIVINE' => 'DIVINE',
                ],
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('level', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'max' => 13,
                    'min' => 0,
                ]
            ])
            ->add('race', TextType::class,[
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('att', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ]
            ])
            ->add('def', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ]
            ])
            ->add('link', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'max' => 6,
                    'min' => 0,
                ]
            ])
            ->add('scale', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'max' => 13,
                    'min' => 0,
                ],
            ])
            ->add('linkMarker', ChoiceType::class,[
                'choices' => [
                    'Top' => 'Top',
                    'Bottom' => 'Bottom',
                    'Left' => 'Left',
                    'Right' => 'Right',
                    'Bottom-Left' => 'Bottom-Left',
                    'Bottom-Right' => 'Bottom-Right',
                    'Top-Left' => 'Top-Left',
                    'Top-Right' => 'Top-Right',
                ],
                'choice_attr' => [
                'Top' => ['class' => 'searchLinkMarkers'],
                'Bottom' => ['class' => 'searchLinkMarkers'],
                'Left' => ['class' => 'searchLinkMarkers'],
                'Right' => ['class' => 'searchLinkMarkers'],
                'Bottom-Left' => ['class' => 'searchLinkMarkers'],
                'Bottom-Right' => ['class' => 'searchLinkMarkers'],
                'Top-Left' => ['class' => 'searchLinkMarkers'],
                'Top-Right' => ['class' => 'searchLinkMarkers'],
                ],
                'expanded' => true,
                'multiple' => true,
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
