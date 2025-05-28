<?php
require_once '../../src/bdd/Bdd.php';

if (!isset($_GET['id_utilisateur'])) {
    die("ID manquant.");
}

$idUser = (int) $_GET['id_utilisateur'];

$bdd = new \bdd\Bdd();
$pdo = $bdd->getBdd();


$stmtUser = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
$stmtUser->execute([$idUser]);

header("Location: ../liste/listeUtilisateurs.php");
exit;
