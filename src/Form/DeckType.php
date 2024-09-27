<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Deck;
use App\Entity\User;
use App\Repository\DeckRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DeckType extends AbstractType
{
    private $deckRepository;

    public function __construct(DeckRepository $deckRepository)
    {
        $this->deckRepository = $deckRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $deck = $options['data'];
        $cards = $deck->getCard();
        //dd($cards);
        $builder
            ->add('title', TextType::class,[
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'attr' => array ('readonly' => true)
            ])
            ->add('closed', CheckboxType::class, [
                'required' => false,
                'value' => 1,
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('picture', ChoiceType::class,[
                'required' => false,
                'choices' => $cards,
                'empty_data' => "null",
                //$choice une instance de cards pour chaque ittération,
                //$key clé de l'élément actuelle de cards
                //$value valeurs de l'élément actuelle de cards
                'choice_label' => function($choice, $key, $value) {
                    
                    return is_object($choice) ? $choice->getName() : 'choice'; // ou toute autre logique pour afficher les éléments
                },
                'choice_value' => function($choice) {
                    
                    return is_object($choice) ? strval($choice->getRefCard()) : '';
                },
                'attr'=> [
                    'class' => 'form-control'
                ],
                
            ])
            ->add('card', EntityType::class, [
                'required' => false,
                'class' => Card::class,
                'choice_label' => 'name',
                //'query_builder' => function (EntityRepository $er): QueryBuilder {
                //    return $er->createQueryBuilder('u')
                    //->where('u.decks = :deck')
                //    ->orderBy('u.name','ASC')
                    //->setParameter('deck', $deck)
                //;},
                'multiple' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'attr' => array ('readonly' => true)
            ])
            ->add('valider', SubmitType::class, [
                'attr' => [
                        'class' => 'btn btn-success'
                ]
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
