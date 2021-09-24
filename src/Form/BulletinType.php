<?php

namespace App\Form;

use App\Entity\Bulletin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BulletinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre', // Le nom du champ une fois affiché à l'Utilisateur
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [ // Le choix proposé à l'utilisateur
                    'Général' => 'general', // La valeur affichée => Valeur retenue
                    'Urgent' => 'urgent',
                    'Divers' => 'divers',
                ],
                'expanded' => true, // Change l'affichage en boutons plutôt que liste
                'multiple' => false, // Permet de choisir plusieurs éléments (donc tableau, impossible
                // ici)
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bulletin::class,
        ]);
    }
}
