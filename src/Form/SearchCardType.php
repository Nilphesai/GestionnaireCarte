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
                    'Normal' => 'Normal Monster,Normal Tuner Monster',
                    'Effect' => 'Effect Monster,Flip Effect Monster,Flip Tuner Effect Monster,Gemini Monster,Spirit Monster,Toon Monster,Union Effect Monster',
                    'Ritual' => 'Ritual Effect Monster,Ritual Monster',
                    'Fusion' => 'Fusion Monster',
                    'Synchro' => 'Synchro Monster,Synchro Tuner Monster',
                    'Xyz' => 'Xyz Monster',
                    'Link' => 'Link Monster',
                    'Pendulum' => 'Pendulum Normal Monster,Pendulum Effect Monster,Pendulum Effect Ritual Monster,Pendulum Effect Fusion Monster,Synchro Pendulum Effect Monster,Xyz Pendulum Effect Monster',
                    'Spell' => 'Spell Card',
                    'Trap' => 'Trap Card',
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
            ->add('race', ChoiceType::class,[
                'choices' => [
                    'Monster' =>[
                        'Aqua' => 'Aqua',
                        'Beast' => 'Beast',
                        'Beast-Warrior' => 'Beast-Warrior',
                        'Creator-God' => 'Creator-God',
                        'Cyberse' => 'Cyberse',
                        'Dinosaur' => 'Dinosaur',
                        'Divine-Beast' => 'Divine-Beast',
                        'Dragon' => 'Dragon',
                        'Fairy' => 'Fairy',
                        'Fiend' => 'Fiend',
                        'Fish' => 'Fish',
                        'Insect' => 'Insect',
                        'Machine' => 'Machine',
                        'Plant' => 'Plant',
                        'Psychic' => 'Psychic',
                        'Pyro' => 'Pyro',
                        'Reptile' => 'Reptile',
                        'Rock' => 'Rock',
                        'Sea Serpent' => 'Sea Serpent',
                        'Spellcaster' => 'Spellcaster',
                        'Thunder' => 'Thunder',
                        'Warrior' => 'Warrior',
                        'Winged Beast' => 'Winged Beast',
                        'Wyrm' => 'Wyrm',
                        'Zombie' => 'Zombie',
                    ],
                    'Spell' =>[
                        'Normal' => 'Normal',
                        'Field' => 'Field',
                        'Equip' => 'Equip',
                        'Continuous' => 'Continuous',
                        'Quick-Play' => 'Quick-Play',
                        'Ritual' => 'Ritual',
                    ],
                    'Trap' =>[
                        'Normal' => 'Normal',
                        'Continuous' => 'Continuous',
                        'Counter' => 'Counter',
                    ]
                    
                ],
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
            ->add('chercher', SubmitType::class, [
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
