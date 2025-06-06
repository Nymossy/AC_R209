<?php

namespace App\Form;

use App\Entity\Note;
use App\Entity\User;
use App\Entity\VieNote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VieNoteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre de la modification'
                ]
            ])
            ->add('description', null, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description détaillée des modifications apportées',
                    'rows' => 5
                ]
            ])
            ->add('modifiedAt', null, [
                'widget' => 'single_text',
                'label' => 'Date de modification'
            ])
            ->add('note', EntityType::class, [
                'class' => Note::class,
                'choice_label' => 'titre',  // Utilise le titre de la note
                'label' => 'Note associée',
                'placeholder' => 'Sélectionner une note'
            ])
            ->add('modifiedBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return $user->getPrenom() . ' ' . $user->getNom();  // Affiche prénom et nom
                },
                'label' => 'Modifié par',
                'placeholder' => 'Sélectionner un utilisateur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VieNote::class,
        ]);
    }
}
