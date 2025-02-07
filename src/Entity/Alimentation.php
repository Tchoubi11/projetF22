<?php

namespace App\Entity;

use App\Repository\AlimentationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: AlimentationRepository::class)]
class Alimentation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "date_heure", type: "datetime")]
    private ?\DateTimeInterface $dateHeure = null;

    #[ORM\Column(length: 255)]
    private ?string $grammage = null;

    #[ORM\Column(length: 255)]
    private ?string $nourriture = null;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: "alimentations")]
    #[ORM\JoinColumn(nullable: false)] 
    private ?Animal $animal = null;

    #[ORM\ManyToMany(targetEntity: RapportVeterinaire::class, inversedBy: 'alimentations')]
    #[ORM\JoinTable(name: 'rapport_alimentation')]
    private Collection $rapportsVeterinaires;

    public function __construct()
    {
        $this->rapportsVeterinaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTimeInterface $dateHeure): static
    {
        $this->dateHeure = $dateHeure;
        return $this;
    }

    public function getGrammage(): ?string
    {
        return $this->grammage;
    }

    public function setGrammage(string $grammage): static
    {
        $this->grammage = $grammage;
        return $this;
    }

    public function getNourriture(): ?string
    {
        return $this->nourriture;
    }

    public function setNourriture(string $nourriture): static
    {
        $this->nourriture = $nourriture;
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

    public function getRapportsVeterinaires(): Collection
    {
        return $this->rapportsVeterinaires;
    }

    public function addRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): static
    {
        if (!$this->rapportsVeterinaires->contains($rapportVeterinaire)) {
            $this->rapportsVeterinaires[] = $rapportVeterinaire;
        }
        return $this;
    }

    public function removeRapportVeterinaire(RapportVeterinaire $rapportVeterinaire): static
    {
        $this->rapportsVeterinaires->removeElement($rapportVeterinaire);
        return $this;
    }
}
