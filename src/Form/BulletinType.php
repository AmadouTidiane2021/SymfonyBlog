<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Bulletin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
            ->add('tags', EntityType::class, [
                'label' => 'Tags',
                'class' => Tag::class, // Le nom de l'Entity que nous voulons attribuer à ce champ
                'choice_label' => 'name', // L'attribut de notre Entity que nous voulons utiliser à comme label
                'expanded' => true, // Affichage en buttons plutôt que liste
                'multiple' => true, // Erreur si false (Nous récupérons une LISTE de tags)
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
