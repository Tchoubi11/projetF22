<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: RapportVeterinaire::class, cascade: ['persist', 'remove'])]
    private Collection $rapportsVeterinaires;
    



    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $poids = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $views = 0;

  

    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: AnimalFeeding::class, cascade: ['persist', 'remove'])]
    private Collection $feedings;

    public function __construct()
    {
        $this->rapportsVeterinaires = new ArrayCollection();
        $this->feedings = new ArrayCollection();
    }

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

    public function getRapportsVeterinaires(): Collection
    {
        return $this->rapportsVeterinaires;
    }


    public function addRapportVeterinaire(RapportVeterinaire $rapport): static
    {
        if (!$this->rapportsVeterinaires->contains($rapport)) {
            $this->rapportsVeterinaires->add($rapport);
            $rapport->setAnimal($this);
        }
        return $this;
    }
    public function removeRapportVeterinaire(RapportVeterinaire $rapport): static
    {
        if ($this->rapportsVeterinaires->removeElement($rapport)) {
            // on vérifie si l'animal actuel est bien assigné avant de le dissocier
            if ($rapport->getAnimal() === $this) {
                $rapport->setAnimal(null);
            }
        }
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
   

    public function getFeedings(): Collection
    {
        return $this->feedings;
    }

    public function addFeeding(AnimalFeeding $feeding): static
    {
        if (!$this->feedings->contains($feeding)) {
            $this->feedings->add($feeding);
            $feeding->setAnimal($this);
        }
        return $this;
    }

    public function removeFeeding(AnimalFeeding $feeding): static
    {
        if ($this->feedings->removeElement($feeding)) {
            if ($feeding->getAnimal() === $this) {
                $feeding->setAnimal(null);
            }
        }
        return $this;
    }
}
