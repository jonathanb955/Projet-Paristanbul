
<?php

use bdd\Bdd;

session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
$connecte = isset($_SESSION['connexion']) && $_SESSION['connexion'] === true;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail magasin</title>
    <link rel="stylesheet" href="../assets/css/detailMag.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://previews.123rf.com/images/jovanas/jovanas1602/jovanas160201149/52031915-logo-avi%C3%B3n-volando-alrededor-del-planeta-tierra-azul.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="d-flex justify-content-center align-items-center position-relative">

        <form action="nosMagasins.php" method="get" class="position-absolute start-0 ms-3">
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </form>

        <div class="btn-group position-absolute end-0 me-3">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-square"></i>
            </button>
            <ul class="dropdown-menu text-center">
                <?php if ($connecte): ?>
                    <span class="dropdown-item-text"><strong>Bienvenue</strong><br><?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?></span>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="espaceClient.php"><i class="bi bi-person-circle"></i> Espace Client</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="?logout=true"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="pageConnexion.php">Connexion <i class="bi bi-person-bounding-box"></i></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="pageInscription.php">Inscription <i class="bi bi-person-plus-fill"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <h1 class="mb-0" style="text-transform: capitalize"> Paristanbul <?= htmlspecialchars($_GET['ville_magasin']) ?> </h1>
    </div>
</header>

<?php
if (isset($_GET['ville_magasin'])) {



    $villeMagasin = $_GET['ville_magasin'];

    $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

    $query = 'SELECT * FROM magasins WHERE ville_magasin = :ville_magasin';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':ville_magasin', $villeMagasin, PDO::PARAM_STR);
    $stmt->execute();

    $magasins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($magasins) {


        foreach ($magasins as $magasin) {
           echo '<div class="film-card">';
    echo '<img src="' . htmlspecialchars($magasin['image']) . '" alt="Image avion">';
    echo '<div class="film-info">';
    echo '<u><h5 style="text-align: center">Le magasin Paristanbul de ' . htmlspecialchars($magasin['ville_magasin']) . ' vous souhaite la bienvenue!</h5></u>';
    echo '<br><p><strong>Adresse :</strong> ' . htmlspecialchars($magasin['rue'])  . ',       '. htmlspecialchars($magasin['cp']) .  '       ' . htmlspecialchars($magasin['ville_magasin']) .'</p>';
    echo '<p><strong>Téléphone :</strong> ' . htmlspecialchars($magasin['num_tel']) . '</p>';
    echo '<p><strong>Horaire ouverture :</strong> ' . htmlspecialchars($magasin['horaire_ouverture']) . '</p>';
    echo '<p><strong>Horaire fermeture :</strong> ' . htmlspecialchars($magasin['horaire_fermeture']) . '</p>';
    echo '<p><strong>Jours d\'ouvertures :</strong> ' . htmlspecialchars($magasin['jours_ouverture']) . '</p>';
    echo '<p><strong>Vidéo du magasin :</strong> ' . htmlspecialchars($magasin['video_magasin']) . '</p>';
    echo '</div>';
    echo '</div>';
}



    }
} else {
    echo '<p>Aucun magasin trouvé pour cette ville.</p>';

}
?>

</body>
</html>
