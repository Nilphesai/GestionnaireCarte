<?php

namespace App\Form;

use App\Entity\deck;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text',TextareaType::class,[
                'attr' => [
                    'rows' => 10, // Nombre de lignes visibles
                    'cols' => 50, // Nombre de colonnes visibles
                    'placeholder' => 'Entrez votre commentaire ici...', // Texte indicatif
                ],
            ])
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'attr' => array ('readonly' => true)
            ])
            ->add('deck', EntityType::class, [
                'class' => deck::class,
                'choice_label' => 'id',
                'attr' => array ('readonly' => true)
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
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
            'data_class' => Post::class,
        ]);
    }
}
