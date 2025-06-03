<?php
namespace App\Form;

use App\Entity\VieNote;
use App\Entity\Note;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VieNoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('modifiedAt')
            ->add('note', EntityType::class, [
                'class' => Note::class,
                'choice_label' => 'titre',  // Affiche le titre de la note au lieu de l'ID
                'label' => 'Note associée',
                'placeholder' => 'Sélectionner une note'
            ])
            ->add('modifiedBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return $user->getPrenom() . ' ' . $user->getNom();  // Affiche le prénom et le nom de l'utilisateur
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