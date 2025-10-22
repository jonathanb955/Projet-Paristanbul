<?php
require_once '../bdd/Bdd.php';
use bdd\Bdd;

// Initialisation de la connexion
$bdd = new Bdd();
$pdo = $bdd->getBdd();

// Vérifier la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../vue/Admin/gestionUserAdmin.php');
    exit;
}

// Vérifier l’ID envoyé
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die("ID utilisateur invalide.");
}

$id = (int) $_POST['id'];

// Vérifier si l’utilisateur existe
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

// Protection : empêcher la suppression de soi-même (optionnel)
session_start();
if (isset($_SESSION['id_utilisateur']) && $_SESSION['id_utilisateur'] == $id) {
    die("Vous ne pouvez pas supprimer votre propre compte administrateur.");
}

// Suppression de l’utilisateur
$stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);

// Redirection vers la page admin avec message de succès
header("Location: ../../vue/Admin/gestionUserAdmin.php?delete=success");
exit;
?>
