<?php

namespace App\Form;

use App\Entity\Demande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_demande', null, [
                'widget' => 'single_text',
                'label' => 'Date de la demande',
            ])
            ->add('demande_id', null, [
                'label' => 'ID de la demande',
            ])
            ->add('resultat_concour', NumberType::class, [
                'label' => 'Résultat du concours',
                'constraints' => new Range([
                    'min' => 0,
                    'max' => 20,
                    'notInRangeMessage' => 'Le résultat du concours doit être entre {{ min }} et {{ max }}.',
                ]),
                'attr' => [
                    'min' => 0,
                    'max' => 20,
                    'step' => 0.01, // Pour permettre des valeurs décimales si besoin
                ],
            ])
            ->add('statut_candidature', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Acceptée' => 'Acceptée',
                    'Rejetée' => 'Rejetée',
                ],
                'label' => 'Statut de la candidature',
                'expanded' => false, // Use radio buttons if true, dropdown if false
                'multiple' => false, // Single choice
                'placeholder' => 'Sélectionnez un statut',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Demande::class,
        ]);
    }
    public function searchByCriteria(?string $criteria): array
    {
        $qb = $this->createQueryBuilder('d');

        if ($criteria) {
            $qb->andWhere('d.id = :criteria OR d.demande_id = :criteria')
                ->setParameter('criteria', $criteria);
        }

        return $qb->getQuery()->getResult();
    }


}
