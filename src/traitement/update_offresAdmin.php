<?php
use bdd\Bdd;

require '../bdd/Bdd.php'; // Connexion PDO

$bdd = new Bdd();
$pdo = $bdd->getBdd();

$id = intval($_POST['id'] ?? 0);
$action = $_POST['action'] ?? null;

// Vérifie si action == "supprimer"
if ($id && $action === 'supprimer') {
    $stmt = $pdo->prepare("DELETE FROM offres_emplois WHERE id_offre = ?");
    $stmt->execute([$id]);
    //
    echo "<p style='color:green;'>Offre d'emploi supprimée avec succès !</p>";
}

// Redirection vers la page des offres
header("Location: ../../vue/Admin/gestionOffreAdmin.php");
exit;
