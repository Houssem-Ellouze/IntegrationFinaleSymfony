<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'First name cannot be empty.'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'First name must be at least {{ limit }} characters long.',
                        'max' => 255
                    ]),
                ],
            ])
            ->add('lastName', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Last name cannot be empty.'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Last name must be at least {{ limit }} characters long.',
                        'max' => 255
                    ]),
                ],
            ])
            ->add('email', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Email cannot be empty.'
                    ]),
                    new Assert\Email([
                        'message' => 'Please provide a valid email address.'
                    ]),
                    new Assert\Length([
                        'max' => 255
                    ]),
                ],
            ])
            ->add('phone', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Phone number cannot be empty.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9]{7,15}$/',
                        'message' => 'Phone number must be between 7 to 15 digits and can optionally start with a "+" sign.'
                    ])
                ],
            ])
            ->add('password', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Password cannot be empty.'
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Password must be at least {{ limit }} characters long.'
                    ]),
                ],
            ])
            ->add('confirmPassword', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please confirm your password.'
                    ])//,
                   // new Assert\EqualTo([
                   //     'value' => $builder->getData()->getPassword(), // This is a workaround to compare passwords.
                    //    'message' => 'Passwords do not match.'
                   // ])
                ],
            ])
            ->add('educationLevel', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Education level cannot be empty.'
                    ]),
                ],
            ])
            ->add('experienceLevel', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Experience level cannot be empty.'
                    ]),
                ],
            ])
            ->add('birthDate', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Birthdate cannot be empty.'
                    ]),
                    new Assert\Date([
                        'message' => 'Please provide a valid birth date.'
                    ]),
                ],
            ])
            ->add('country', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Country cannot be empty.'
                    ]),
                ],
            ])
            ->add('source', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Source cannot be empty.'
                    ]),
                ],
            ])
            ->add('cvFilename', null, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please upload your CV.'
                    ]),
                ],
            ])
            ->add('jobAlerts', null, [
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['YES', 'NO'],
                        'message' => 'Please select a valid choice for job alerts.'
                    ]),
                ],
            ])
            ->add('promotions', null, [
                'constraints' => [
                    new Assert\Choice([
                        'choices' => ['YES', 'NO'],
                        'message' => 'Please select a valid choice for promotions.'
                    ]),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
