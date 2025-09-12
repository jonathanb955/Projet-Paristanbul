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

// Dossiers de stockage
    $chemin_telechargement = __DIR__ . "/telechargement/candidatures/";
    if (!is_dir($chemin_telechargement)) {
        mkdir($chemin_telechargement, 0777, true);
    }

    // Initialiser les chemins
    $lien_cv = null;

    // Gestion du CV
    // Nettoyer nom et prénom pour éviter les caractères spéciaux dans le nom de fichier
    $nettoyer_nom   = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($nom));
    $nettoyer_prenom = preg_replace("/[^a-zA-Z0-9]/", "_", strtolower($prenom));

// Gestion du CV
    if (!empty($_FILES['cv']['name'])) {
        $cvTmp = $_FILES['cv']['tmp_name'];
        $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);

        // Nom du fichier : cv_nom_prenom.ext
        $nom_cv = "cv_{$nettoyer_nom}_{$nettoyer_prenom}." . $extension;
        $cvDest = $chemin_telechargement . $nom_cv;

        if (move_uploaded_file($cvTmp, $cvDest)) {
            $lien_cv = "téléchargements/candidatures/" . $nom_cv;
        }
    }


    // Insertion BDD
    $sql = $pdo->prepare("
        INSERT INTO candidatures
        (nom, prenom, email, date_naissance, langues, adresse, telephone, permis,
         experiences, lettre_motivation, ref_offre,lien_cv)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)
    ");

    $ok = $sql->execute([
        $nom, $prenom, $email, $date_naissance, $langues, $adresse, $telephone,
        $permis, $experiences, $lettre, $ref_offre,$lien_cv
    ]);

    if ($ok) {
        header("Location: merci.php"); // redirige vers une page de remerciement
        exit;
    }
}
