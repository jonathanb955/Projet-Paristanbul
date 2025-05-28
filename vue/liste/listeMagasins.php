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
    <title>Liste utilisateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/liste.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://previews.123rf.com/images/jovanas/jovanas1602/jovanas160201149/52031915-logo-avi%C3%B3n-volando-alrededor-del-planeta-tierra-azul.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <div class="d-flex justify-content-center align-items-center position-relative">

        <form action="../pageAdmin.php" method="get" class="position-absolute start-0 ms-3">
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
                    <li><a class="dropdown-item" href="?logout=true"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="../pageConnexion.php">Connexion <i class="bi bi-person-bounding-box"></i></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../pageInscription.php">Inscription <i class="bi bi-person-plus-fill"></i></a></li>
                <?php endif; ?>
            </ul>
        </div>

        <h1 class="mb-0" style="text-transform: capitalize">Supermarché Paristanbul</h1>
    </div>
</header>
<?php
require_once '../../src/bdd/Bdd.php';
$bdd = new \bdd\Bdd();
$pdo = $bdd->getBdd();
$query = "SELECT * FROM produits";
$stmt = $pdo->prepare($query);
$stmt->execute();
$magasins = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4><strong>Liste des magasins</strong></h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle">
                <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Ville</th>
                    <th>Adresse</th>
                    <th>Téléphone</th>
                    <th>Jours d'ouverture</th>
                    <th>Horaires</th>
                    <th>Vidéo</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($magasins as $magasin): ?>
                    <tr>
                        <td><?= htmlspecialchars($magasin['id_magasin']) ?></td>
                        <td><?= htmlspecialchars($magasin['ville_magasin']) ?></td>
                        <td><?= htmlspecialchars($magasin['rue']) ?> - <?= htmlspecialchars($magasin['cp']) ?></td>
                        <td><?= htmlspecialchars($magasin['num_tel']) ?></td>
                        <td><?= htmlspecialchars($magasin['jours_ouverture']) ?></td>
                        <td>
                            <?= htmlspecialchars($magasin['horaire_ouverture']) ?> - <?= htmlspecialchars($magasin['horaire_fermeture']) ?>
                        </td>

                        <td>
                            <?php if (!empty($magasin['video_magasin'])): ?>
                                <a href="<?= htmlspecialchars($magasin['video_magasin']) ?>" target="_blank">Voir</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($magasin['image'])): ?>
                                <img src="<?= htmlspecialchars($magasin['image']) ?>" alt="Image magasin" style="max-width: 80px;">
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="../modifAdmin/modifMagasin.php?id_magasin=<?= $magasin['id_magasin'] ?>" class="btn btn-warning btn-sm me-2">Modifier</a>
                            <a href="../suppAdmin/suppMagasin.php?id_magasin=<?= $magasin['id_magasin'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce magasin ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
