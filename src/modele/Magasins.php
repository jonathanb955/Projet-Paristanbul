<?php

namespace modele;

class Magasins
{
private $idMagasin;
private $villleMagasin;
private $rue;
private $cp;
private $image;
private $numTel;
private $horaireOuverture;
private $horaireFermeture;
private $joursOuverture;
private $videoMagasin;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    private function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            // Convertir snake_case en CamelCase
            $camelKey = str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            $method = 'set' . $camelKey;

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIdMagasin()
    {
        return $this->idMagasin;
    }

    /**
     * @param mixed $idMagasin
     */
    public function setIdMagasin($idMagasin)
    {
        $this->idMagasin = $idMagasin;
    }

    /**
     * @return mixed
     */
    public function getVillleMagasin()
    {
        return $this->villleMagasin;
    }

    /**
     * @param mixed $villleMagasin
     */
    public function setVillleMagasin($villleMagasin)
    {
        $this->villleMagasin = $villleMagasin;
    }

    /**
     * @return mixed
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * @param mixed $rue
     */
    public function setRue($rue)
    {
        $this->rue = $rue;
    }

    /**
     * @return mixed
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param mixed $cp
     */
    public function setCp($cp)
    {
        $this->cp = $cp;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getNumTel()
    {
        return $this->numTel;
    }

    /**
     * @param mixed $numTel
     */
    public function setNumTel($numTel)
    {
        $this->numTel = $numTel;
    }

    /**
     * @return mixed
     */
    public function getHoraireOuverture()
    {
        return $this->horaireOuverture;
    }

    /**
     * @param mixed $horaireOuverture
     */
    public function setHoraireOuverture($horaireOuverture)
    {
        $this->horaireOuverture = $horaireOuverture;
    }

    /**
     * @return mixed
     */
    public function getHoraireFermeture()
    {
        return $this->horaireFermeture;
    }

    /**
     * @param mixed $horaireFermeture
     */
    public function setHoraireFermeture($horaireFermeture)
    {
        $this->horaireFermeture = $horaireFermeture;
    }

    /**
     * @return mixed
     */
    public function getJoursOuverture()
    {
        return $this->joursOuverture;
    }

    /**
     * @param mixed $joursOuverture
     */
    public function setJoursOuverture($joursOuverture)
    {
        $this->joursOuverture = $joursOuverture;
    }

    /**
     * @return mixed
     */
    public function getVideoMagasin()
    {
        return $this->videoMagasin;
    }

    /**
     * @param mixed $videoMagasin
     */
    public function setVideoMagasin($videoMagasin)
    {
        $this->videoMagasin = $videoMagasin;
    }



}