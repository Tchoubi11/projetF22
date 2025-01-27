<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
class AnimalFeeding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: RapportVeterinaire::class, inversedBy: 'feedings')]
    #[ORM\JoinColumn(name: 'rapport_veterinaire_id', referencedColumnName: 'id', nullable: false)]
    private ?RapportVeterinaire $rapport = null;


    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'feedings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;


    #[ORM\Column(type: 'string', length: 255)]
    private ?string $food = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $feedingTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRapportVeterinaire(): ?RapportVeterinaire
    {
        return $this->rapport;
    }

    public function setRapportVeterinaire(?RapportVeterinaire $rapport): self
    {
        $this->rapport = $rapport;
        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;
        return $this;
    }

    public function getFood(): ?string
    {
        return $this->food;
    }

    public function setFood(string $food): self
    {
        $this->food = $food;
        return $this;
    }

    public function getFeedingTime(): ?\DateTimeInterface
    {
        return $this->feedingTime;
    }

    public function setFeedingTime(?\DateTimeInterface $feedingTime): self
    {
        $this->feedingTime = $feedingTime;
        return $this;
    }
}
