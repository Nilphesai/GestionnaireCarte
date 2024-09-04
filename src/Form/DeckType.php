<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Deck;
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

class DeckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options, Deck $deck = null): void
    {
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
            ->add('picture', TextType::class,[
                'required' => false,
                'attr'=> [
                    'class' => 'form-control'
                ]
            ])
            ->add('card', EntityType::class, [
                'required' => false,
                'class' => Card::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('u')
                    //->where('u.decks = :deck')
                    ->orderBy('u.name','ASC')
                    //->setParameter('deck', $deck)
                ;},
                'multiple' => true,
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
