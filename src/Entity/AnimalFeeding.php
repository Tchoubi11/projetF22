<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AnimalFeeding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'feedings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    #[ORM\Column(type: "text")]
    private ?string $food = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $feedingTime = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFood(): ?string
    {
        return $this->food;
    }

    public function setFood(string $food): static
    {
        $this->food = $food;

        return $this;
    }

    public function getFeedingTime(): ?\DateTimeInterface
    {
        return $this->feedingTime;
    }

    public function setFeedingTime(\DateTimeInterface $feedingTime): static
    {
        $this->feedingTime = $feedingTime;

        return $this;
    }
}
