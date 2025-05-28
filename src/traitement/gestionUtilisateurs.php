<?php
require_once '../repository/UtilisateursRepository.php';
require_once '../modele/Utilisateurs.php';

use modele\Utilisateurs;

if (isset($_POST['modifier'])) {
    $utilisateur = new Utilisateurs([
        'idUtilisateur' => $_POST['id_utilisateur'],
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'role' => $_POST['role'],
        'mdp' => '',
    ]);

    $repo = new UtilisateursRepository();
    $repo->modifierUtilisateur($utilisateur);

    header('Location: ../../vue/pageReussite/modifUtilisateurReussite.html');
    exit;
}
