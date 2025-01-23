<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(collection: "animals")]
class AnimalDocument
{
    #[MongoDB\Id]
    private ?string $id = null;

    #[MongoDB\Field(type: "string")]
    private ?string $prenom = null;

    #[MongoDB\Field(type: "int")]
    private int $views = 0;  // Valeur par défaut à 0

    // Getters et Setters
    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;
        return $this;
    }

    public function incrementViews(): self
    {
        $this->views++;
        return $this;
    }
}
