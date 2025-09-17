<?php

use bdd\Bdd;

require '../bdd/Bdd.php'; // connexion PDO

$bdd = new Bdd();
$pdo = $bdd ->getBdd() ;


$id = intval($_POST['id'] ?? 0);
$action = $_POST['action'] ?? null;

// Associer action -> statut
$validActions = [
    'retenir' => 'retenu',
    'refuser' => 'refuse',
    'archiver' => 'archive'
];

if ($id && isset($validActions[$action])) {
    $stmt = $pdo->prepare("UPDATE candidatures SET statut = ? WHERE id = ?");
    $stmt->execute([$validActions[$action], $id]);
}

// Retour à la liste après mise à jour
header("Location: ../../vue/Admin/candidatureAdmin.php");
exit;