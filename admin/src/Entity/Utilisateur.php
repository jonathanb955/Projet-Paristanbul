<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: "utilisateurs")]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_utilisateur", type: "integer")]
    private ?int $idUtilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(name: "email", length: 255, unique: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    /**
     * @var Collection<int, LogActivite>
     */
    #[ORM\OneToMany(targetEntity: LogActivite::class, mappedBy: 'utilisateur')]
    private Collection $logActivites;

    public function __construct()
    {
        $this->logActivites = new ArrayCollection();
    }

    // --- GETTERS / SETTERS ---

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;
        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;
        return $this;
    }

    // --- Obligatoire pour UserInterface & PasswordAuthenticatedUserInterface ---

    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    public function getUserIdentifier(): string
    {
        return $this->mail ?? '';
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Nettoie les données sensibles si tu en stockes temporairement
    }

    /**
     * @return Collection<int, LogActivite>
     */
    public function getLogActivites(): Collection
    {
        return $this->logActivites;
    }

    public function addLogActivite(LogActivite $logActivite): static
    {
        if (!$this->logActivites->contains($logActivite)) {
            $this->logActivites->add($logActivite);
            $logActivite->setUtilisateur($this);
        }

        return $this;
    }

    public function removeLogActivite(LogActivite $logActivite): static
    {
        if ($this->logActivites->removeElement($logActivite)) {
            // set the owning side to null (unless already changed)
            if ($logActivite->getUtilisateur() === $this) {
                $logActivite->setUtilisateur(null);
            }
        }

        return $this;
    }
}
