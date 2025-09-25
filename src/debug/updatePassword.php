<?php
require_once '../bdd/Bdd.php';

require_once '../bdd/Bdd.php';

use bdd\Bdd;

// 👈 ajoute cette ligne SI ta classe bdd est dans le namespace bdd


$bdd = Bdd::getInstance();

// Hash du mot de passe "01234567891011"
$hash = password_hash("01234567891011", PASSWORD_DEFAULT);

// Requête SQL pour mettre à jour le mot de passe de l'utilisateur
$sql = "UPDATE utilisateurs SET mdp = :mdp WHERE email = :email";
$stmt = $bdd->prepare($sql);
$stmt->execute([
    ':mdp' => $hash,
    ':email' => '48@gmail.com'
]);

echo "✅ Mot de passe mis à jour avec succès.";
