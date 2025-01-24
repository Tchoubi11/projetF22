<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document]
class AnimalDocument
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: "string")]
    private ?string $prenom = null;

    #[ODM\Field(type: "int")]
    private int $views = 0; // Initialisation des vues à 0

    //getters et setters
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

   
    public function getViews(): int
    {
        return $this->views;
    }

      public function setViews(int $views): self
    {
        $this->views = $views;
        return $this;
    }

   
    public function incrementViews(): void
    {
        $this->views++;
    }
}
