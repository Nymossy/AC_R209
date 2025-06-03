<?php

namespace App\Form;

use App\Entity\Galerie;
use App\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class GalerieForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de l\'image',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir un nom pour l\'image']),
                ]
            ])
            ->add('noteLiaison', EntityType::class, [
                'class' => Note::class,
                'choice_label' => 'titre',
                'label' => 'Note associée',
                'placeholder' => 'Choisissez une note',
                'required' => true
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (JPG, PNG, GIF)',
                'mapped' => false,  // Ce champ n'est pas directement mappé à une propriété
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '10M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPG, PNG ou GIF)',
                    ])
                ],
                'help' => 'Taille maximale: 10 MB. Formats acceptés: JPG, PNG, GIF.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Galerie::class,
        ]);
    }
}
