<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class RapportVeterinaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detail = null;

    #[ORM\ManyToOne(targetEntity: Animal::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "L'animal est obligatoire.")]
    private ?Animal $animal = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $habitatComment = null;

    #[ORM\OneToMany(targetEntity: AnimalFeeding::class, mappedBy: 'rapportVeterinaire', cascade: ['persist', 'remove'])]
    #[Assert\Valid]
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

    public function setDetail(?string $detail): static
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
            $this->feedings[] = $feeding;
            $feeding->setRapportVeterinaire($this);
        }
        return $this;
    }

    public function removeFeeding(AnimalFeeding $feeding): static
    {
        if ($this->feedings->removeElement($feeding)) {
            if ($feeding->getRapportVeterinaire() === $this) {
                $feeding->setRapportVeterinaire(null);
            }
        }
        return $this;
    }
}
