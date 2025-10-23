<?php
require_once '../bdd/Bdd.php';
use bdd\Bdd;

$bdd = new Bdd();
$pdo = $bdd->getBdd();

// Vérifier l'ID de l'offre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID offre manquant ou invalide.");
}

$id = (int) $_GET['id'];

// Récupération de l'offre
$stmt = $pdo->prepare("SELECT * FROM offres_emplois WHERE id_offre = ?");
$stmt->execute([$id]);
$offre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offre) {
    die("Offre introuvable.");
}

// Récupération des magasins
$sqlVilles = $pdo->prepare("SELECT ville_magasin FROM magasins");
$sqlVilles->execute();
$magasins = $sqlVilles->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $secteur = trim($_POST['secteur'] ?? '');
    $titre_poste = trim($_POST['titre_poste'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $departement = trim($_POST['departement'] ?? '');
    $type_contrat = trim($_POST['type_contrat'] ?? '');
    $detail_poste = trim($_POST['detail_poste'] ?? '');

    $errors = [];

    if (empty($secteur)) $errors[] = "Le secteur d'activité est requis.";
    if (empty($titre_poste)) $errors[] = "Le titre du poste est requis.";
    if (empty($ville)) $errors[] = "La ville est requise.";

    if (empty($errors)) {
        $sql = "UPDATE offres_emplois 
                SET secteur_activite=?, titre_poste=?, ville=?, departement=?, type_contrat=?, detail_poste=? 
                WHERE id_offre=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$secteur, $titre_poste, $ville, $departement, $type_contrat, $detail_poste, $id]);

        header("Location: ../../vue/Admin/gestionOffreAdmin.php?edit=success");
        exit;
    } else {
        echo '<div class="alert alert-error">' . implode('<br>', $errors) . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Modifier Offre</title>
    <link rel="stylesheet" href="../../assets/css/admin.css" />
    <link rel="stylesheet" href="../../assets/css/gestionOffreAdmin.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
<aside class="sidebar">
    <div class="brand">
        <a href="../../vue/index.php"><img src="../../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" /></a>
    </div>

    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="../../vue/pageAdmin.php"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="menu-item" href="../../vue/Admin/nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Nos magasins</span></a>

        <div class="menu-title">Administration</div>
        <a class="menu-item" href="../../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>
        <a class="menu-item active" href="../../vue/Admin/gestionOffreAdmin.php"><i class="bi bi-people"></i><span>Offres</span></a>
    </nav>

    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <h1>Modifier une offre</h1>
        <div class="top-actions">
            <a href="../../vue/Admin/gestionOffreAdmin.php" class="btn ghost">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </header>

    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Éditer l'offre</h2>
            </div>

            <form class="form" method="post">
                <div class="grid two">
                    <label>
                        <span>Secteur d'activité</span>
                        <input type="text" name="secteur" value="<?= htmlspecialchars($offre['secteur_activite']) ?>" required>
                    </label>
                    <label>
                        <span>Titre du poste</span>
                        <input type="text" name="titre_poste" value="<?= htmlspecialchars($offre['titre_poste']) ?>" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Ville</span>
                        <select name="ville" required>
                            <?php foreach ($magasins as $magasin): ?>
                                <option value="<?= htmlspecialchars($magasin['ville_magasin']) ?>"
                                        <?= $magasin['ville_magasin'] === $magasin['ville_magasin'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($magasin['ville_magasin']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>
                        <span>Département</span>
                        <select name="departement" required>
                            <?php
                            $departements = ['95','94','93','92','91','78','77','75','60'];
                            foreach ($departements as $dep): ?>
                                <option value="<?= $dep ?>" <?= $offre['departement'] === $dep ? 'selected' : '' ?>><?= $dep ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>

                <label>
                    <span>Type de contrat</span>
                    <input type="text" name="type_contrat" value="<?= htmlspecialchars($offre['type_contrat']) ?>" required>
                </label>

                <label>
                    <span>Détail du poste</span>
                    <textarea name="detail_poste" rows="5"><?= htmlspecialchars($offre['detail_poste']) ?></textarea>
                </label>

                <div class="form-actions">
                    <button type="submit" class="btn primary">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>
                    <a href="../../vue/Admin/gestionOffreAdmin.php" class="btn ghost">
                        <i class="bi bi-x-lg"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
</html>
