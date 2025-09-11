<?php

namespace bdd;
use \PDO;

class Bdd
{
    private $bdd;

    public function __construct()
    {
        try {
            $this->bdd = new PDO( 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8',
                'root',
                '');


            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    public function getBdd()
    {
        return $this->bdd;
    }


}