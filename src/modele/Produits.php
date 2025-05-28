<?php


namespace modele;


class Produits


{
    private $idProduit;
    private $nomProduit;
    private $photo;

    private $refCategorie;

    private $refSousCategorie;


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

    //getter setter

    /**
     * @return mixed
     */
    public function getIdProduit()
    {
        return $this->idProduit;
    }

    /**
     * @param mixed $idProduit
     */
    public function setIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;
    }

    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param mixed $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return mixed
     */
    public function getNomProduit()
    {
        return $this->nomProduit;
    }

    /**
     * @param mixed $nomProduit
     */
    public function setNomProduit($nomProduit)
    {
        $this->nomProduit = $nomProduit;
    }

    /**
     * @return mixed
     */
    public function getRefCategorie()
    {
        return $this->refCategorie;
    }

    /**
     * @param mixed $refCategorie
     */
    public function setRefCategorie($refCategorie)
    {
        $this->refCategorie = $refCategorie;
    }

    /**
     * @return mixed
     */
    public function getRefSousCategorie()
    {
        return $this->refSousCategorie;
    }

    /**
     * @param mixed $refSousCategorie
     */
    public function setRefSousCategorie($refSousCategorie)
    {
        $this->refSousCategorie = $refSousCategorie;
    }

}

