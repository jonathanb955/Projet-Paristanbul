<?php

use bdd\Bdd;

require_once __DIR__ . '/../bdd/Bdd.php';





class UtilisateursRepository {

    public function connexion(\modele\Utilisateurs $utilisateur){
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $req->execute(array(
            'email' => $utilisateur->getEmail()
        ));
        $donnees = $req->fetch();
        if ($donnees) {
            return new \modele\Utilisateurs([
                'id_utilisateur' => $donnees['id_utilisateur'],
                'nom' => $donnees['nom'],
                'prenom' => $donnees['prenom'],
                'email' => $donnees['email'],
                'mdp' => $donnees['mdp'],
                'role' => $donnees['role']
            ]);
        }

        return null;
    }
    public function inscription(\modele\Utilisateurs $utilisateur) {
        try {
            $bdd = new Bdd();
            $database = $bdd->getBdd();
            $hashedMdp = password_hash($utilisateur->getMdp(), PASSWORD_BCRYPT);

            $req = $database->prepare("INSERT INTO utilisateurs (nom, prenom, email, mdp, role) 
                                   VALUES (:nom, :prenom, :email, :mdp, :role)");

            $req->execute(array(
                'nom' => $utilisateur->getNom(),
                'prenom' => $utilisateur->getPrenom(),
                'email' => $utilisateur->getEmail(),
                'mdp' => $hashedMdp,
                'role' => $utilisateur->getRole(),
            ));

            return $utilisateur;
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }


    public function nombreUtilisateur(){
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT COUNT(id_utilisateur) FROM utilisateurs');
        $req->execute();
        $result = $req->fetch();
        return $result[0];
    }

    public function verifDoublonEmail( $utilisateur)
    {
        $bdd = new Bdd();
        $datebase = $bdd->getBdd();
        $req = $datebase->prepare('SELECT email FROM utilisateurs WHERE email=:email');
        $req->execute(array(
            "email"=>$utilisateur->getEmail()
        ));
        $result = $req->fetch();
        var_dump($result);
        if($result){
            return true;
        }
        else{
            return false;
        }
    }


    public function modifierUtilisateur(\modele\Utilisateurs $utilisateur) {
        try {
            $bdd = new Bdd();
            $database = $bdd->getBdd();
            $req = $database->prepare("UPDATE utilisateurs 
            SET nom = :nom, prenom = :prenom, email = :email, role = :role  
            WHERE id_utilisateur = :id_utilisateur");

            $req->execute([
                'nom' => $utilisateur->getNom(),
                'prenom' => $utilisateur->getPrenom(),
                'email' => $utilisateur->getEmail(),
                'role' => $utilisateur->getRole(),
                'id_utilisateur' => $utilisateur->getIdUtilisateur()
            ]);
            return true;
        } catch (PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }





}
?>