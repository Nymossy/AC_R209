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
            ->add('titre')
            ->add('description')
            ->add('modifiedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('note', EntityType::class, [
                'class' => Note::class,
                'choice_label' => 'id',
            ])
            ->add('modifiedBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
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
