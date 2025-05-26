<?php

use modele\Utilisateurs;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once"../modele/Utilisateurs.php";
require_once "../repository/UtilisateursRepository.php";

if (!empty($_POST["email"]) &&
    !empty($_POST["nom"]) &&
    !empty($_POST["prenom"]) &&
    !empty($_POST["mdp"]) &&
    !empty($_POST["mdpC"])) {

    if (strlen($_POST["mdp"]) < 12) {
        header("Location: ../../vue/pageInscription.php?parametre=mdp");
        exit();
    }


    if(($_POST["mdp"]==$_POST["mdpC"])) {
        $hashpassword = password_hash($_POST["mdp"], PASSWORD_DEFAULT);
        session_start();

        $utilisateurRepository = new UtilisateursRepository();
        $nbutilisateur=$utilisateurRepository->nombreUtilisateur();
        if($nbutilisateur==0) {
            $role="admin";
        }else{
            $role="utilisateur";
        }
        $utilisateur = new Utilisateurs([
            "email" => $_POST["email"],
            "nom" => $_POST["nom"],
            "prenom" => $_POST["prenom"],
            "mdp" => $hashpassword,
            "role"=> $role,

        ]);
        $verif=$utilisateurRepository->verifDoublonEmail($utilisateur);
        if ($verif) {
            header("Location: ../../vue/pageInscription.php?parametre=doublon");
        }else{
            $utilisateur = $utilisateurRepository->inscription($utilisateur);

            $_SESSION["email"] = $_POST["email"];
            $_SESSION["mdp"] = $_POST["mdp"];
            $_SESSION["role"] = $role;
            $_SESSION["nom"] = $_POST["nom"];
            $_SESSION["prenom"] = $_POST["prenom"];

            var_dump($utilisateur);
            if ($utilisateur->getRole() == "utilisateur") {;
                header("Location: ../../index.php");
            }

            header("Location: ../../vue/pageReussite/pageInscriptionReussite.html");
        }

    } else {
        header("Location: ../../vue/pageInscription.php?parametre=mdp");
    }

}

else{
    header("Location: ../../vue/pageInscription.php?parametre=champsVides");
}
