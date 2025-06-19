<?php

namespace App\Entity;

use App\Repository\OffresEmploisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffresEmploisRepository::class)]
class OffresEmplois
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $secteur_activite = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_poste = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $departement = null;

    #[ORM\Column(length: 255)]
    private ?string $type_contrat = null;

    #[ORM\Column(length: 255)]
    private ?string $detail_poste = null;

    /**
     * @var Collection<int, Candidatures>
     */
    #[ORM\OneToMany(targetEntity: Candidatures::class, mappedBy: 'ref_offre', cascade: ['persist', 'remove'],
        orphanRemoval: true)]
    private Collection $candidatures;


    public function __construct()
    {
        $this->candidatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSecteurActivite(): ?string
    {
        return $this->secteur_activite;
    }

    public function setSecteurActivite(string $secteur_activite): static
    {
        $this->secteur_activite = $secteur_activite;

        return $this;
    }

    public function getTitrePoste(): ?string
    {
        return $this->titre_poste;
    }

    public function setTitrePoste(string $titre_poste): static
    {
        $this->titre_poste = $titre_poste;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(string $type_contrat): static
    {
        $this->type_contrat = $type_contrat;

        return $this;
    }

    public function getDetailPoste(): ?string
    {
        return $this->detail_poste;
    }

    public function setDetailPoste(string $detail_poste): static
    {
        $this->detail_poste = $detail_poste;

        return $this;
    }

    /**
     * @return Collection<int, Candidatures>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidatures $candidature): static
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->setRefOffre($this);
        }

        return $this;
    }

    public function removeCandidature(Candidatures $candidature): static
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getRefOffre() === $this) {
                $candidature->setRefOffre(null);
            }
        }

        return $this;
    }




}
