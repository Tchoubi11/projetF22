<?php

namespace App\Entity;

use App\Repository\RapportVeterinaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportVeterinaireRepository::class)]
class RapportVeterinaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 50)]
    private ?string $detail = null;

    #[ORM\OneToOne(inversedBy: 'rapportVeterinaire', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $observations = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $habitatComment = null;//colonne est spécifique à l'entité RapportVeterinaire utilisée pour un animal particulier.

    #[ORM\OneToMany(targetEntity: AnimalFeeding::class, mappedBy: 'rapportVeterinaire', cascade: ['persist', 'remove'])]
    private Collection $feedings;

    public function __construct()
    {
        $this->feedings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): static
    {
        $this->detail = $detail;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getObservations(): ?string
    {
        return $this->observations;
    }

    public function setObservations(?string $observations): static
    {
        $this->observations = $observations;

        return $this;
    }

    public function getHabitatComment(): ?string
    {
        return $this->habitatComment;
    }

    public function setHabitatComment(?string $habitatComment): static
    {
        $this->habitatComment = $habitatComment;

        return $this;
    }

    public function getFeedings(): Collection
    {
        return $this->feedings;
    }

    public function addFeeding(AnimalFeeding $feeding): static
    {
        if (!$this->feedings->contains($feeding)) {
            $this->feedings->add($feeding);
            $feeding->setRapportVeterinaire($this);
        }

        return $this;
    }

    public function removeFeeding(AnimalFeeding $feeding): static
    {
        if ($this->feedings->removeElement($feeding)) {
            // Set the owning side to null if the relationship is removed
            if ($feeding->getRapportVeterinaire() === $this) {
                $feeding->setRapportVeterinaire(null);
            }
        }

        return $this;
    }
}
