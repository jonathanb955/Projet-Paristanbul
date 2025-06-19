<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categories $ref_categorie = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?SousCategories $ref_sousCategorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getRefCategorie(): ?Categories
    {
        return $this->ref_categorie;
    }

    public function setRefCategorie(?Categories $ref_categorie): static
    {
        $this->ref_categorie = $ref_categorie;

        return $this;
    }

    public function getRefSousCategorie(): ?SousCategories
    {
        return $this->ref_sousCategorie;
    }

    public function setRefSousCategorie(?SousCategories $ref_sousCategorie): static
    {
        $this->ref_sousCategorie = $ref_sousCategorie;

        return $this;
    }
}
