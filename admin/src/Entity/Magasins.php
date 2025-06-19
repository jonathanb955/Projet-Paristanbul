<?php

namespace App\Entity;

use App\Repository\MagasinsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagasinsRepository::class)]
class Magasins
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_magasin = null;

    #[ORM\Column(length: 255)]
    private ?string $ville_magasin = null;

    #[ORM\Column(length: 255)]
    private ?string $rue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 15)]
    private ?string $cp = null;

    #[ORM\Column(length: 15)]
    private ?string $num_tel = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $horaire_ouverture = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $horaire_fermeture = null;

    #[ORM\Column(length: 255)]
    private ?string $jours_ouverture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video_magasin = null;

    public function getId(): ?int
    {
        return $this->id_magasin;
    }

    public function getVilleMagasin(): ?string
    {
        return $this->ville_magasin;
    }

    public function setVilleMagasin(string $ville_magasin): static
    {
        $this->ville_magasin = $ville_magasin;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(string $num_tel): static
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getHoraireOuverture(): ?\DateTime
    {
        return $this->horaire_ouverture;
    }

    public function setHoraireOuverture(\DateTime $horaire_ouverture): static
    {
        $this->horaire_ouverture = $horaire_ouverture;

        return $this;
    }

    public function getHoraireFermeture(): ?\DateTime
    {
        return $this->horaire_fermeture;
    }

    public function setHoraireFermeture(\DateTime $horaire_fermeture): static
    {
        $this->horaire_fermeture = $horaire_fermeture;

        return $this;
    }

    public function getJoursOuverture(): ?string
    {
        return $this->jours_ouverture;
    }

    public function setJoursOuverture(string $jours_ouverture): static
    {
        $this->jours_ouverture = $jours_ouverture;

        return $this;
    }

    public function getVideoMagasin(): ?string
    {
        return $this->video_magasin;
    }

    public function setVideoMagasin(string $video_magasin): static
    {
        $this->video_magasin = $video_magasin;

        return $this;
    }
}
