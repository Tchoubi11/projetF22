<?php

namespace App\Entity;

use App\Repository\RapportVeterinaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportVeterinaireRepository::class)]
class RapportVeterinaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $habitatComment = null; //colonne est spécifique à l'entité RapportVeterinaire,utilisée pour stocker des informations ou commentaires relatifs à l'habitat dans le contexte d'un rapport vétérinaire pour un animal particulier.

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 50)]
    private ?string $detail = null;

    #[ORM\OneToOne(inversedBy: 'rapportVeterinaire', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)] // un rapport associé par animal
    private ?Animal $animal = null;
      
     #[ORM\Column(type:"text", nullable:true)]
     
    private ?string $observations = null;

    public function getHabitatComment(): ?string
    {
    return $this->habitatComment;
    }

    public function setHabitatComment(?string $habitatComment): static
    {
    $this->habitatComment = $habitatComment;

    return $this;
    }
    
    public function getObservations(): ?string
    {
        return $this->observations;
    }

    
    public function setObservations(?string $observations): self
    {
        $this->observations = $observations;
        return $this;
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
}
