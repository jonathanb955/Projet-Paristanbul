<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

// Vérifier si l'offre existe
if (isset($_GET['id'])) {
    $id_offre = (int) $_GET['id'];

    $req = $pdo->prepare("SELECT * FROM offres_emplois WHERE id_offre = :id");
    $req->execute(['id' => $id_offre]);
    $offre = $req->fetch(PDO::FETCH_ASSOC);

    if (!$offre) {
        die("Offre introuvable !");
    }
} else {
    die("Aucune offre sélectionnée !");
}

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    $insert = $pdo->prepare("
        INSERT INTO candidatures (nom, prenom, email, telephone, ref_offre)
        VALUES (:nom, :prenom, :email, :telephone, :ref_offre)
    ");
    $insert->execute([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone,
        'ref_offre' => $id_offre
    ]);

    echo "<p class='text-success'>Votre candidature a été envoyée avec succès pour le poste <strong>".$offre['titre_poste']."</strong>.</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul</title>
    <link rel="stylesheet" href="../assets/css/quiSommesNous.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/postuler.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/quiSommesNous.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Custom Style -->

</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo"><a href="index.php"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" style="width: 300px"></a></div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Accueil</a></li>
            <li><a href="index.php" class="active">Nos magasins</a></li>
            <li><a href="quiSommesNous.html">Notre histoire</a></li>
            <li><a href="">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="pageInscription.php" class="btn-light">Inscription</a>
            <a href="pageConnexion.php" class="btn-dark">Connexion</a>
        </div>
    </nav>
</header>

