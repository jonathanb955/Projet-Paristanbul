<?php
use bdd\Bdd;
require_once "../../src/bdd/Bdd.php";

$bddObj = new Bdd();
$pdo = $bddObj->getBdd();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Debug PDO

$id_magasin = (int) ($_POST['id_magasin'] ?? $_GET['id'] ?? 0);
$message = "";
$messageType = ""; // success | error

// Vérifier que l'ID est valide
if ($id_magasin <= 0) {
    $message = "Identifiant de magasin invalide.";
    $messageType = "error";
} else {
    // Récupération du magasin à modifier
    $stmt = $pdo->prepare("SELECT * FROM magasins WHERE id_magasin = ?");
    $stmt->execute([$id_magasin]);
    $magasin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$magasin) {
        $message = "Magasin introuvable.";
        $messageType = "error";
    }
}

// === TRAITEMENT DU FORMULAIRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_magasin'])) {
    try {
        $ville = trim($_POST['ville_magasin']);
        $rue = trim($_POST['rue']);
        $cp = trim($_POST['cp']);
        $num_tel = trim($_POST['num_tel']);
        $horaire_ouverture = $_POST['horaire_ouverture'] ?: null;
        $horaire_fermeture = $_POST['horaire_fermeture'] ?: null;
        $jours_ouverture = isset($_POST['jours_ouverture']) ? implode(',', $_POST['jours_ouverture']) : '';

        $sql = "UPDATE magasins SET 
                    ville_magasin = :ville,
                    rue = :rue,
                    cp = :cp,
                    num_tel = :num_tel,
                    horaire_ouverture = :ouverture,
                    horaire_fermeture = :fermeture,
                    jours_ouverture = :jours
                WHERE id_magasin = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
                ':ville' => $ville,
                ':rue' => $rue,
                ':cp' => $cp,
                ':num_tel' => $num_tel,
                ':ouverture' => $horaire_ouverture,
                ':fermeture' => $horaire_fermeture,
                ':jours' => $jours_ouverture,
                ':id' => $id_magasin
        ]);

        $affected = $stmt->rowCount();
        if ($affected > 0) {
            header("Location: ../../vue/Admin/nosMagasinsAdmin.php?success=1");
            exit;
        } else {
            $message = "Aucune modification détectée.";
            $messageType = "warning";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la mise à jour : " . $e->getMessage();
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Modifier un magasin</title>
    <link rel="stylesheet" href="../../assets/css/nosMagasinAdmin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .layout { display: flex; justify-content: center; align-items: flex-start; padding: 2rem; }
        .card { max-width: 650px; width: 100%; margin: 2rem auto; }
        .form-control { display: flex; flex-direction: column; margin-bottom: 1rem; }
        .form-control label { font-weight: 600; margin-bottom: 0.3rem; }
        .form-control input { padding: 8px 10px; border: 1px solid #ccc; border-radius: 6px; }
        fieldset { border: 1px solid #ddd; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; }
        fieldset legend { font-weight: 600; }
        .jours { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-top: 0.5rem; }
        .form-actions { display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem; }
        .alert { padding: 10px 15px; border-radius: 6px; margin-bottom: 1rem; font-weight: 500; }
        .alert.success { background: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
        .alert.error { background: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
        .alert.warning { background: #fff3cd; color: #664d03; border: 1px solid #ffecb5; }
    </style>
</head>

<body>
<aside class="sidebar">
    <div class="brand">
        <a href="../pageAdmin.php"><img src="../../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" /></a>
    </div>

    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="../pageAdmin.php"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>

        <div class="menu-title">Administration</div>
        <a class="menu-item" href="gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
        <a class="menu-item" href="candidatureAdmin.php"><i class="bi bi-file-earmark-text"></i><span>Candidatures</span></a>
        <a class="menu-item" href="gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
        <a class="menu-item active" href="nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Magasins</span></a>
    </nav>

    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <h1><i class="bi bi-pencil-square"></i> Modifier un magasin</h1>
    </header>

    <section class="layout">
        <div class="card">
            <?php if ($message): ?>
                <div class="alert <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <?php if (!empty($magasin)): ?>
                <div class="card-head">
                    <h2><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($magasin['ville_magasin']) ?></h2>
                </div>

                <form method="POST" class="form">
                    <input type="hidden" name="id_magasin" value="<?= htmlspecialchars($magasin['id_magasin']) ?>">

                    <div class="form-control">
                        <label>Ville</label>
                        <input type="text" name="ville_magasin" value="<?= htmlspecialchars($magasin['ville_magasin']) ?>" required>
                    </div>

                    <div class="form-control">
                        <label>Rue</label>
                        <input type="text" name="rue" value="<?= htmlspecialchars($magasin['rue']) ?>" required>
                    </div>

                    <div class="form-control">
                        <label>Code postal</label>
                        <input type="text" name="cp" value="<?= htmlspecialchars($magasin['cp']) ?>" required>
                    </div>

                    <div class="form-control">
                        <label>Téléphone</label>
                        <input type="text" name="num_tel" value="<?= htmlspecialchars($magasin['num_tel']) ?>" required>
                    </div>

                    <div class="grid two">
                        <div class="form-control">
                            <label>Heure d'ouverture</label>
                            <input type="time" name="horaire_ouverture" value="<?= htmlspecialchars($magasin['horaire_ouverture']) ?>">
                        </div>

                        <div class="form-control">
                            <label>Heure de fermeture</label>
                            <input type="time" name="horaire_fermeture" value="<?= htmlspecialchars($magasin['horaire_fermeture']) ?>">
                        </div>
                    </div>

                    <fieldset>
                        <legend>Jours d'ouverture</legend>
                        <div class="jours">
                            <?php
                            $jours = ["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
                            $joursOuverts = array_map('trim', explode(',', $magasin['jours_ouverture'] ?? ''));
                            foreach ($jours as $jour): ?>
                                <label>
                                    <input type="checkbox" name="jours_ouverture[]" value="<?= $jour ?>"
                                            <?= in_array($jour, $joursOuverts) ? 'checked' : '' ?>>
                                    <?= $jour ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>

                    <div class="form-actions">
                        <a href="nosMagasinsAdmin.php" class="btn ghost">Annuler</a>
                        <button type="submit" class="btn"><i class="bi bi-check2-circle"></i> Enregistrer</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </section>
</main>
</body>
</html>
