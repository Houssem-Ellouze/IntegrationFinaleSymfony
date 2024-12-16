<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_demande = null;

    #[ORM\Column]
    private ?int $demande_id = null;

    #[ORM\Column]
    private ?float $resultat_concour = null;

    #[ORM\Column(length: 20)]
    private ?string $statut_candidature = null;

    public const STATUTS = ['En attente', 'Acceptée', 'Rejetée'];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDemande(): ?\DateTimeInterface
    {
        return $this->date_demande;
    }

    public function setDateDemande(\DateTimeInterface $date_demande): static
    {
        $this->date_demande = $date_demande;

        return $this;
    }

    public function getDemandeId(): ?int
    {
        return $this->demande_id;
    }

    public function setDemandeId(int $demande_id): static
    {
        $this->demande_id = $demande_id;

        return $this;
    }

    #[Assert\Range(
        min: 0,
        max: 20,
        notInRangeMessage: "Le résultat du concours doit être entre {{ min }} et {{ max }}."
    )]
    public function getResultatConcour(): ?float
    {
        return $this->resultat_concour;
    }

    public function setResultatConcour(float $resultat_concour): static
    {
        $this->resultat_concour = $resultat_concour;

        return $this;
    }

    public function getStatutCandidature(): ?string
    {
        return $this->statut_candidature;
    }

    public function setStatutCandidature(string $statut_candidature): static
    {
        if (!in_array($statut_candidature, self::STATUTS, true)) {
            throw new \InvalidArgumentException("Statut invalide: $statut_candidature. Valeurs possibles: " . implode(', ', self::STATUTS));
        }

        $this->statut_candidature = $statut_candidature;

        return $this;
    }

}
