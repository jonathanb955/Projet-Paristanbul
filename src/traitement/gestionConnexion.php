<?php
require_once '../modele/Utilisateurs.php';
require_once "../repository/UtilisateursRepository.php";
require_once "../bdd/Bdd.php";
session_start();

if (empty($_POST['emailCo']) || empty($_POST['mdpCo'])) {
    header("Location: ../../vue/pageConnexion.php?parametre=infoManquante");
    exit();
} else {
    $utilisateur = new \modele\Utilisateurs([
        'email' => $_POST["emailCo"]
    ]);
    $utilisateurRepository = new UtilisateursRepository();
    $utilisateur = $utilisateurRepository->connexion($utilisateur);


    if ($utilisateur !== null) {

            // DEBUG : Affiche les deux mots de passe
            echo "<div style='padding: 20px; font-family: monospace; background: #f5f5f5; border: 1px solid #ccc'>";
            echo "<strong>Mot de passe saisi :</strong> " . htmlspecialchars($_POST['mdpCo']) . "<br>";
            echo "<strong>Mot de passe hashé (BDD) :</strong> " . htmlspecialchars($utilisateur->getMdp()) . "<br>";
            echo "<strong>Résultat de password_verify :</strong> ";
            var_dump(password_verify($_POST['mdpCo'], $utilisateur->getMdp()));
            echo "</div>";
            exit;
        }

        // Si tu veux garder le login fonctionnel, commente les lignes ci-dessus après debug
    if (password_verify($_POST['mdpCo'], $utilisateur->getMdp()))
        $_SESSION['id_utilisateur'] = $utilisateur->getIdUtilisateur();
            $_SESSION['email'] = $utilisateur->getEmail();
            $_SESSION['nom'] = $utilisateur->getNom();
            $_SESSION['prenom'] = $utilisateur->getPrenom();
            $_SESSION['role'] = $utilisateur->getRole();
            $_SESSION['connexion'] = true;

            if ($utilisateur->getRole() == "admin") {
                $_SESSION["connexionAdmin"] = true;
            }

            if (isset($_SESSION['redirect_after_login'])) {
                $url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $url");
            } else {
                header("Location: ../../index.php");
            }
            exit();
        } else {
            header("Location: ../../vue/pageConnexion.php?parametre=emailmdpInvalide");
            exit();
        }
}
