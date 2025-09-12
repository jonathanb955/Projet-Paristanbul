<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Récupérer les champs
    $nom             = $_POST['nom'] ?? '';
    $prenom          = $_POST['prenom'] ?? '';
    $telephone       = $_POST['telephone'] ?? '';
    $email           = $_POST['email'] ?? '';
    $date_naissance  = $_POST['date_naissance'] ?? '';
    $langues         = $_POST['langues'] ?? '';
    $adresse         = $_POST['adresse'] ?? '';
    $permis          = $_POST['permis'] ?? '';
    $experiences     = $_POST['experiences'] ?? '';
    $lettre          = $_POST['lettre_motivation'] ?? '';
    $ref_offre = null;
    if (!empty($_POST['ref_offre'])) {
        $ref_offre = (int) $_POST['ref_offre'];
    } elseif (!empty($_GET['id'])) {
        $ref_offre = (int) $_GET['id'];
    } else {
        // aucune offre précisée -> erreur gérable
        die('Erreur : identifiant de l\'offre manquant.');
    }




    // Insertion BDD
    $sql = $pdo->prepare("
        INSERT INTO candidatures
        (nom, prenom, email, date_naissance, langues, adresse, telephone, permis,
         experiences, lettre_motivation, ref_offre)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $ok = $sql->execute([
        $nom, $prenom, $email, $date_naissance, $langues, $adresse, $telephone,
        $permis, $experiences, $lettre, $ref_offre
    ]);

    if ($ok) {
        header("Location: merci.php"); // redirige vers une page de remerciement
        exit;
    }
}
