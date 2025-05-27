<?php
session_start();
$connecte = isset($_SESSION['connexion']) && $_SESSION['connexion'] === true;
$prenom = $_SESSION['prenom'] ?? '';
$nom = $_SESSION['nom'] ?? '';
$role = $_SESSION['role'] ?? '';


if (!isset($_SESSION['connexion']) || $_SESSION['connexion'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: pageConnexion.php?error=unauthorized');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://previews.123rf.com/images/jovanas/jovanas1602/jovanas160201149/52031915-logo-avi%C3%B3n-volando-alrededor-del-planeta-tierra-azul.jpg"> <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet"> <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;700&display=swap" rel="stylesheet"> <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet"> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet"> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> <link rel="stylesheet" href="../assets/css/admin.css"> </head>
<body>

<div class="main-content">

    <div class="btn-group position-absolute end-0 me-3">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-square"></i>
        </button>
        <ul class="dropdown-menu text-center">
            <?php if ($connecte): ?>
                <span class="dropdown-item-text"><strong>Bienvenue</strong><br><?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?></span>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="?logout=true"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
            <?php else: ?>
                <li><a class="dropdown-item" href="pageConnexion.php">Connexion <i class="bi bi-person-bounding-box"></i></a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="pageInscription.php">Inscription <i class="bi bi-person-plus-fill"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="dashboard-header">

        <h1 style="text-align: center">Tableau de bord</h1>
        <p>Bienvenue dans l'espace d'administration</p>
    </div>

    <h3 class="text-white mb-3">Gestion</h3>
    <div class="card-grid">
        <div class="admin-card">
            <h5>Utilisateurs</h5>
            <a href="listeUtilisateurs.php">Accéder</a>
        </div>
        <div class="admin-card">
            <h5>Vols</h5>
            <a href="listeVols.php">Accéder</a>
        </div>
        <div class="admin-card">
            <h5>Avions</h5>
            <a href="listeAvions.php">Accéder</a>
        </div>
        <div class="admin-card">
            <h5>Pilotes</h5>
            <a href="listePilotes.php">Accéder</a>
        </div>
    </div>

    <h3 class="text-white mt-5 mb-3">Ajouts</h3>
    <div class="card-grid">
        <div class="admin-card">
            <h5>Ajout d'un vol</h5>
            <a href="ajoutVols.php">Accéder</a>
        </div>
        <div class="admin-card">
            <h5>Ajout d'un avion</h5>
            <a href="ajoutsAvion.php">Accéder</a>
        </div>
        <div class="admin-card">
            <h5>Ajout d'un pilote</h5>
            <a href="ajoutPilote.php">Accéder</a>
        </div>

    </div>



</div>
<br>
<br>

<form action="../index.php" method="get" class="bouton-retour">
    <button type="submit" class="btn  partenaire" style="background-color: #0d6efd ; color: white; text-align: center">Retour</button>
</form>
</body>
</html>