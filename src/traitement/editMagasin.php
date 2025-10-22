<?php
require_once '../bdd/Bdd.php';
use bdd\Bdd;

$bdd = new Bdd();
$pdo = $bdd->getBdd();

// Vérification de l'ID du magasin
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID magasin manquant ou invalide.");
}

$id = (int) $_GET['id'];

// Récupération du magasin
$stmt = $pdo->prepare("SELECT * FROM magasins WHERE id_magasin = ?");
$stmt->execute([$id]);
$magasin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$magasin) {
    die("Magasin introuvable.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ville = trim($_POST['ville_magasin'] ?? '');
    $rue = trim($_POST['rue'] ?? '');
    $cp = trim($_POST['cp'] ?? '');
    $num_tel = trim($_POST['num_tel'] ?? '');
    $horaire_ouverture = trim($_POST['horaire_ouverture'] ?? '');
    $horaire_fermeture = trim($_POST['horaire_fermeture'] ?? '');

    $errors = [];

    if ($ville === '') $errors[] = "La ville est requise.";
    if ($rue === '') $errors[] = "La rue est requise.";
    if ($cp === '') $errors[] = "Le code postal est requis.";
    if ($num_tel === '') $errors[] = "Le numéro de téléphone est requis.";
    if ($horaire_ouverture === '' || $horaire_fermeture === '') $errors[] = "Les horaires sont requis.";

    if (empty($errors)) {
        $sql = "UPDATE magasins 
                SET ville_magasin=?, rue=?, cp=?, num_tel=?, horaire_ouverture=?, horaire_fermeture=? 
                WHERE id_magasin=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ville, $rue, $cp, $num_tel, $horaire_ouverture, $horaire_fermeture, $id]);

        header("Location: ../../vue/Admin/nosMagasinsAdmin.php?edit=success");
        exit;
    } else {
        echo '<div class="alert alert-error" style="padding:10px; background:#f8d7da; color:#721c24; margin:10px 0; border-radius:4px;">'
                . implode('<br>', $errors) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Modifier Magasin</title>
    <link rel="stylesheet" href="../../assets/css/admin.css" />
    <link rel="stylesheet" href="../../assets/css/nosMagasinAdmin.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>

<body>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="brand">
        <a href="../../vue/index.php">
            <img src="../../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" />
        </a>
    </div>

    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="../../vue/pageAdmin.php"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="menu-item active" href="../../vue/Admin/nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Nos magasins</span></a>

        <div class="menu-title">Administration</div>
        <a class="menu-item" href="../../vue/Admin/gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
        <a class="menu-item" href="../../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>
        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>

    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<!-- Contenu principal -->
<main class="main">
    <header class="topbar">
        <h1>Modifier un magasin</h1>
        <div class="top-actions">
            <a href="../../vue/Admin/nosMagasinsAdmin.php" class="btn ghost">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </header>

    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Éditer le magasin</h2>
            </div>

            <form class="form" method="post">
                <div class="grid two">
                    <label>
                        <span>Ville</span>
                        <input type="text" name="ville_magasin" value="<?= htmlspecialchars($magasin['ville_magasin']) ?>" required>
                    </label>
                    <label>
                        <span>Rue</span>
                        <input type="text" name="rue" value="<?= htmlspecialchars($magasin['rue']) ?>" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Code postal</span>
                        <input type="text" name="cp" value="<?= htmlspecialchars($magasin['cp']) ?>" required>
                    </label>
                    <label>
                        <span>Téléphone</span>
                        <input type="text" name="num_tel" value="<?= htmlspecialchars($magasin['num_tel']) ?>" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Horaire d'ouverture</span>
                        <input type="time" name="horaire_ouverture" value="<?= htmlspecialchars(substr($magasin['horaire_ouverture'], 0, 5)) ?>" required>
                    </label>
                    <label>
                        <span>Horaire de fermeture</span>
                        <input type="time" name="horaire_fermeture" value="<?= htmlspecialchars(substr($magasin['horaire_fermeture'], 0, 5)) ?>" required>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn primary">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>
                    <a href="../../vue/Admin/nosMagasinsAdmin.php" class="btn ghost">
                        <i class="bi bi-x-lg"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
</html>
