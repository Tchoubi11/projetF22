<?php

namespace App\Entity;

use App\Repository\HabitatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitatRepository::class)]
class Habitat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 1000)]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaireHabitat = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $horairesOuverture = null;

    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'habitat', cascade: ['persist', 'remove'])]
    private Collection $animaux;

    #[ORM\ManyToMany(targetEntity: Image::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinTable(name: 'habitat_images')]
    private Collection $images;

    public function __construct()
    {
        $this->animaux = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

     // Getters et Setters

     public function getComment(): ?string
     {
         return $this->comment;
     }
 
     public function setComment(?string $comment): static
     {
         $this->comment = $comment;
 
         return $this;
     }
     public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCommentaireHabitat(): ?string
    {
        return $this->commentaireHabitat;
    }

    public function setCommentaireHabitat(?string $commentaireHabitat): static
    {
        $this->commentaireHabitat = $commentaireHabitat;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getHorairesOuverture(): ?string
    {
        return $this->horairesOuverture;
    }

    public function setHorairesOuverture(?string $horairesOuverture): static
    {
        $this->horairesOuverture = $horairesOuverture;

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
            $animal->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): static
    {
        if ($this->animaux->removeElement($animal)) {
            if ($animal->getHabitat() === $this) {
                $animal->setHabitat(null);
            }
        }

        return $this;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        $this->images->removeElement($image);

        return $this;
    }
}
