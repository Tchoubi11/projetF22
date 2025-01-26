<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    //  Propriétés 

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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $views = 0;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: Alimentation::class, cascade: ['persist', 'remove'])]
    private Collection $alimentations;

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: AnimalFeeding::class)]
    private Collection $feedings;

    //  Constructeur 

    public function __construct()
    {
        $this->alimentations = new ArrayCollection();
        $this->feedings = new ArrayCollection();
    }

    //  Méthodes 

    //  Getter et Setter pour ID 
    public function getId(): ?int
    {
        return $this->id;
    }

    //  Getter et Setter pour Prenom 
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    //  Getter et Setter pour Race 
    public function getRace(): ?Race
    {
        return $this->race;
    }

    public function setRace(?Race $race): static
    {
        $this->race = $race;
        return $this;
    }

    //  Getter et Setter pour Image 
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    //  Getter et Setter pour Habitat 
    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;
        return $this;
    }

    //  Getter et Setter pour Rapport Vétérinaire 
    public function getRapportVeterinaire(): ?RapportVeterinaire
    {
        return $this->rapportVeterinaire;
    }

    public function setRapportVeterinaire(?RapportVeterinaire $rapportVeterinaire): static
    {
        $this->rapportVeterinaire = $rapportVeterinaire;
        return $this;
    }

    //  Getter et Setter pour Etat 
    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    //  Getter et Setter pour Poids 
    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(?float $poids): static
    {
        $this->poids = $poids;
        return $this;
    }

    //  Getter et Setter pour Date de Naissance 
    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    //  Gestion des Vues 
    public function getViews(): int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;
        return $this;
    }

    public function incrementViews(): void
    {
        $this->views++;
    }

    // Gestion des Alimentations 
    public function getAlimentations(): Collection
    {
        return $this->alimentations;
    }

    public function addAlimentation(Alimentation $alimentation): static
    {
        if (!$this->alimentations->contains($alimentation)) {
            $this->alimentations->add($alimentation);
            $alimentation->setAnimal($this);
        }

        return $this;
    }

    public function removeAlimentation(Alimentation $alimentation): static
    {
        if ($this->alimentations->removeElement($alimentation)) {
            if ($alimentation->getAnimal() === $this) {
                $alimentation->setAnimal(null);
            }
        }

        return $this;
    }

    //  Gestion des Feedings 
    public function getFeedings(): Collection
    {
        return $this->feedings;
    }
}
