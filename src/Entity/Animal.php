<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnimalRepository;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(targetEntity: Race::class, inversedBy: 'animaux')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Race $race = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Habitat::class, inversedBy: 'animaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habitat $habitat = null;

    #[ORM\OneToOne(mappedBy: 'animal', cascade: ['persist', 'remove'])]
    private ?RapportVeterinaire $rapportVeterinaire = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(type: "float", nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;

        return $this;
    }

    public function getRapportVeterinaire(): ?RapportVeterinaire
    {
        return $this->rapportVeterinaire;
    }

    public function setRapportVeterinaire(?RapportVeterinaire $rapportVeterinaire): static
    {
        $this->rapportVeterinaire = $rapportVeterinaire;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

   
}
