<?php
require_once "../../src/bdd/Bdd.php";
use bdd\Bdd;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Ajouter un magasin — Administration</title>
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
        <a class="menu-item active" href="nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Nos magasins</span></a>

        <div class="menu-title">Administration</div>
        <a class="menu-item" href="gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
        <a class="menu-item" href="gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>
</aside>

<!-- Contenu principal -->
<main class="main">
    <header class="topbar">
        <h1>Ajouter un magasin</h1>
        <div class="top-actions">
            <a href="nosMagasinsAdmin.php" class="btn ghost"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </header>

    <section class="layout">
        <aside class="sidepanel card" style="max-width: 700px; margin: 0 auto;">
            <div class="card-head"><h2>Nouveau magasin</h2></div>
            <form class="form" method="post" action="../../src/traitement/ajoutMagasin.php" enctype="multipart/form-data">
                <div class="grid two">
                    <label>
                        <span>Ville</span>
                        <input type="text" name="ville_magasin" placeholder="Ex : Paris" required>
                    </label>
                    <label>
                        <span>Rue / Adresse</span>
                        <input type="text" name="rue" placeholder="Ex : 12 rue de Rivoli" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Code postal</span>
                        <input type="text" name="cp" placeholder="75001" required>
                    </label>
                    <label>
                        <span>Téléphone</span>
                        <input type="text" name="num_tel" placeholder="+33 6 12 34 56 78" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Heure d’ouverture</span>
                        <input type="time" name="horaire_ouverture" required>
                    </label>
                    <label>
                        <span>Heure de fermeture</span>
                        <input type="time" name="horaire_fermeture" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Jours d’ouverture</span>
                        <input type="text" name="jours_ouverture" placeholder="Lundi, Mardi, Mercredi..." required>
                    </label>
                    <label>
                        <span>Image (URL)</span>
                        <input type="url" name="image" placeholder="https://exemple.com/image.jpg" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Vidéo (URL)</span>
                        <input type="url" name="video_magasin" placeholder="https://youtube.com/...">
                    </label>
                    <label>
                        <span>Latitude</span>
                        <input type="text" name="latitude" placeholder="48.8566">
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Longitude</span>
                        <input type="text" name="longitude" placeholder="2.3522">
                    </label>
                </div>

                <button class="btn primary" type="submit">Créer le magasin</button>
            </form>
        </aside>
    </section>

    <footer class="footer">
        <small>© 2025 — Back-office Paristanbul • Magasins</small>
    </footer>
</main>

</body>
</html>
