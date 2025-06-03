<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Note;
use App\Entity\Tag;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NoteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre de la note'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description détaillée de la note',
                    'rows' => 5
                ]
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Échéance',
                'widget' => 'single_text',
                'required' => false
            ])
            // Priorité avec select au lieu de slider
            ->add('priorite', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Très basse (1)' => 1,
                    'Basse (2)' => 2,
                    'Basse-moyenne (3)' => 3,
                    'Moyenne (4)' => 4,
                    'Moyenne (5)' => 5,
                    'Moyenne-haute (6)' => 6,
                    'Haute (7)' => 7,
                    'Haute (8)' => 8,
                    'Très haute (9)' => 9,
                    'Urgente (10)' => 10,
                ],
                'placeholder' => 'Choisir un niveau de priorité',
                'required' => true,
            ])
            ->add('tag', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'nom',  // Utilise le champ "nom" au lieu de l'ID
                'label' => 'Tags',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'attr' => [
                    'class' => 'selectpicker',
                    'data-live-search' => 'true'
                ]
            ])
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'nom',  // Utilise le champ "nom" au lieu de l'ID
                'label' => 'État',
                'required' => false,
                'placeholder' => 'Choisir un état'
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return $user->getPrenom() ?: $user->getEmail();
                },
                'label' => 'Utilisateur',
                'required' => false,
                'placeholder' => 'Choisir un utilisateur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
