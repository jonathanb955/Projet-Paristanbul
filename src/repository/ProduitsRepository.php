<?php

namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';



use modele\Produits;
use bdd\Bdd;


class ProduitsRepository extends Bdd
{

    public function ajoutProduit(Produits $produit) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('INSERT INTO produits (nom_produit, photo, ref_categorie, ref_sous_categorie) VALUES (:nom_produit, :photo, :ref_categorie, :ref_sous_categorie)');
        $req->execute([
            'id_produit' => $produit->getIdProduit(),
            'nom_produit' => $produit->getNomProduit(),
            'photo' => $produit->getPhoto(),
            'ref_categorie' => $produit->getRefCategorie(),
            'ref_sous_categorie' => $produit->getRefSousCategorie(),


        ]);
        return $produit;
    }
    public function modifProduit(Produits $produit) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('UPDATE produits
        SET nom_produit = :nom_produit, 
            photo = :photo,
                ref_categorie = :ref_categorie,
                    ref_sous_categorie = ref_sous_categorie :
        WHERE id_produit = :id_produit');

        $req->execute([
            'id_produit' => $produit->getIdProduit(),
            'nom_produit' => $produit->getNomProduit(),
            'photo' => $produit->getPhoto(),
            'ref_categorie' => $produit->getRefCategorie(),
            'ref_sous_categorie' => $produit->getRefSousCategorie()
        ]);

        return $produit;
    }




}