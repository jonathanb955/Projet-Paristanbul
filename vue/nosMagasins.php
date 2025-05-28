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
    <title>Catalogue des Vols</title>
    <link rel="stylesheet" href="../assets/css/catalogue.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://previews.123rf.com/images/jovanas/jovanas1602/jovanas160201149/52031915-logo-avi%C3%B3n-volando-alrededor-del-planeta-tierra-azul.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="d-flex justify-content-center align-items-center position-relative">

        <form action="index.php" method="get" class="position-absolute start-0 ms-3">
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

        <h1 class="mb-0" style="text-transform: capitalize">Supermarché Paristanbul</h1>
    </div>
</header>

<h1 class="title">✈️ <u>Nos vols disponibles</u>✈️</h1>


<form action="" method="GET">
    <input type="text" name="search" placeholder="Rechercher une destination..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Rechercher</button>
</form>

<div class="catalogue">

    <?php

    $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');


    $search = isset($_GET['search']) ? $_GET['search'] : '';


    if ($search) {
        $req = $pdo->prepare('
        SELECT nom_magasin, MIN(photo) as photo
        FROM magasins
        WHERE ville_magasin LIKE :search
        GROUP BY ville_magasin
    ');
        $req->execute(['search' => '%' . $search . '%']);
    } else {
        $req = $pdo->query('
        SELECT nom_magasin, MIN(photo) as photo
        FROM magasins
        GROUP BY nom_magasin
    ');
    }



     ($magasin = $req->fetchAll()) ;
    foreach ($magasin as $magasin) { $nom = $magasin['nom_magasin'];
        $photo = $magasin['photo'];
        echo '<div class="film-card">';
        echo '<img src="' . htmlspecialchars($photo) . '" alt="Photo de ' . htmlspecialchars($nom) . '" class="destination-photo">';
        echo '<div class="film-info">';
        echo '<u><h2>Ville: ' . htmlspecialchars($nom) . '</h2></u>';
        echo '<form action="reservation.php" method="get">
              <button type="submit" class="btn btn-dark" name="destination" value="' . htmlspecialchars($nom) . '">Voir les vols</button>
          </form>';
        echo '</div>';
        echo '</div>';}



    ?>

</div>

</body>
</html>
