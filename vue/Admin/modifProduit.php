<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/ProduitsRepository.php';
require_once '../../src/modele/Produits.php';
use modele\Utilisateurs;
use bdd\Bdd;

if (!isset($_GET['id_produit'])) {
    die("ID manquant.");
}

$idProduit = (int)$_GET['id_produit'];

$repo = new UtilisateursRepository();
$bdd = new Bdd();
$db = $bdd->getBdd();
$stmt = $db->prepare("SELECT * FROM produits WHERE id_utilisateur = :id_produit");
$stmt->execute([':id_produit' => $idProduit]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Utilisateur non trouvé.");
}
?>

<link href="../../assets/css/modif.css" rel="stylesheet">
<form action="../../src/traitement/gestionUtilisateurs.php" method="post">
    <h1><u>Modication Utilisateur</u></h1>
    <input type="hidden" name="id_utilisateur" value="<?= $data['id_utilisateur'] ?>">
    <label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($data['nom']) ?>"></label><br>
    <label>Prénom : <input type="text" name="prenom" value="<?= htmlspecialchars($data['prenom']) ?>"></label><br>
    <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>"></label><br>
    <label>Role : <input type="text" name="role" value="<?= htmlspecialchars($data['role']) ?>"></label><br>
    <button type="submit" name="modifier">Modifier</button>
    <p class="footer"> <a href="../pageAdmin.php">Retourner à l'accueil</a></p>
</form>
