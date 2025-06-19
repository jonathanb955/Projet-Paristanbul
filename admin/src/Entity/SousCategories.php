<?php

namespace App\Entity;

use App\Repository\SousCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SousCategoriesRepository::class)]
class SousCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_sousCategorie = null;

    #[ORM\ManyToOne(inversedBy: 'sousCategories')]
    private ?Categories $ref_categorie = null;

    /**
     * @var Collection<int, Produits>
     */
    #[ORM\OneToMany(targetEntity: Produits::class, mappedBy: 'ref_sousCategorie')]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSousCategorie(): ?string
    {
        return $this->nom_sousCategorie;
    }

    public function setNomSousCategorie(string $nom_sousCategorie): static
    {
        $this->nom_sousCategorie = $nom_sousCategorie;

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

    /**
     * @return Collection<int, Produits>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setRefSousCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getRefSousCategorie() === $this) {
                $produit->setRefSousCategorie(null);
            }
        }

        return $this;
    }
}
