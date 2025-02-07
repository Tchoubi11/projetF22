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
    #[ORM\JoinColumn(name: 'rapport_veterinaire_id', referencedColumnName: 'id', nullable: true)]
    private ?RapportVeterinaire $rapportVeterinaire = null;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'feedings')]
    #[ORM\JoinColumn(name: 'animal_id', referencedColumnName: 'id', nullable: false)]
    private ?Animal $animal = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Food must not be blank')] 
    private ?string $food = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Assert\GreaterThanOrEqual("today", message: "The feeding time must be today or in the future")] 
    private ?\DateTimeInterface $feedingTime = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private $quantity; 

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRapportVeterinaire(): ?RapportVeterinaire
    {
        return $this->rapportVeterinaire;
    }

    public function setRapportVeterinaire(?RapportVeterinaire $rapportVeterinaire): self
    {
        $this->rapportVeterinaire = $rapportVeterinaire;
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
