<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ShearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
           $builder
               ->add('titre', TextType::class, array(
                'required' => false
                ))
                ->add('user', EntityType::class,[
                    'placeholder' => 'Choose an author',
                    'class' => User::class,
                    'choice_label' => 'nom',
                    'required' => false,
                ])
                ->add('statut', ChoiceType::class, [
                    'choices'  => [
                         'Choix' => NULL,
                        'Publié' => 'Publié',
                        'Brouillon' => 'Brouillon',
                    ],
                ])

           ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
