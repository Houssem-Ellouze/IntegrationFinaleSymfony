<?php

namespace App\Repository;

use App\Entity\Recruteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RecruteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('profilePicture', FileType::class, [
                'label' => 'Profile Picture (JPEG/PNG)',
                'mapped' => false,  // Prevent automatic mapping to the entity
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',  // Maximum file size
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG or PNG).',
                    ]),
                ],
            ])
            ->add('nom')
            ->add('email', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email ne peut pas être vide.',
                    ]),
                    new Email([
                        'message' => 'Veuillez entrer un email valide.',
                    ]),
                ],
            ])
            ->add('password')

            ->add('statut')
            ->add('entreprise')
            ->add('secteurActivite', ChoiceType::class, [
            'choices' => [
                'Informatique' => 'informatique',
                'Santé' => 'sante',
                'Éducation' => 'education',
                'Finance' => 'finance',
                'Construction' => 'construction',
                'Commerce' => 'commerce',
                'Transport' => 'transport',
                'Hôtellerie' => 'hotellerie',
                'Agriculture' => 'agriculture',
                'Administration' => 'administration',
            ],
            'label' => 'Secteur d\'activité',
        ])
            ->add('tailleEntreprise', RangeType::class, [
                'label' => 'Taille de lentreprise',
                'attr' => [
                    'min' => 1,
                    'max' => 1000,
                    'step' => 1,
                ],
            ])
            ->add('dateCreation',DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('adresse')
            ->add('telephone', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le téléphone ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^\+?[0-9]{8,15}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide.',
                    ]),
                ],
            ])
            ->add('descriptionEntreprise')
            ->add('confirmationEmail')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recruteur::class,
        ]);
    }
}
