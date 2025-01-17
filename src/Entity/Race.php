<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'race', targetEntity: Animal::class)]
    private Collection $animaux;

    public function __construct()
    {
        $this->animaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getAnimaux(): Collection
    {
        return $this->animaux;
    }

    public function addAnimal(Animal $animal): static
    {
        if (!$this->animaux->contains($animal)) {
            $this->animaux->add($animal);
            $animal->setRace($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animaux->removeElement($animal)) {
            // je désassocie l'animal si nécessaire
            if ($animal->getRace() === $this) {
                $animal->setRace(null);
            }
        }

        return $this;
    }
}
