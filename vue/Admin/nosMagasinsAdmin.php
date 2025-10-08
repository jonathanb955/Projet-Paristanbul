<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Nos magasins</title>
    <link rel="stylesheet" href=../../assets/css/nosMagasinAdmin.css>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar mini (mêmes items que ton admin) -->
<aside class="sidebar">
    <div class="brand">
        <div class="brand">
            <a href="../../vue/index.php"><img src="../../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" /></a>
        </div>   </div>

    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="../../vue/pageAdmin.php"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <div class="menu-title">Contenus</div>

        <a class="menu-item" href="../../vue/Admin/promoAdmin.php"><i class="bi bi-megaphone"></i><span>Promotions</span></a>
        <div class="menu-title">Administration</div>
        <a class="menu-item" href="gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>

        <a class="menu-item" href="../../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>

        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>

    <div class="sidebar-footer">
        <div class="mini-user">

        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<!-- Main -->
<main class="main">
    <!-- Topbar -->
    <header class="topbar">
        <h1>Nos magasins — Administration</h1>
        <div class="top-actions">
            <button class="btn ghost"><i class="bi bi-upload"></i> Import CSV</button>
            <button class="btn"><i class="bi bi-plus-circle"></i> Ajouter un magasin</button>
        </div>
    </header>

    <!-- Filtres -->
    <section class="filters">
        <form class="filters-bar" action="#" method="get">
            <div class="field">
                <i class="bi bi-search"></i>
                <input type="search" placeholder="Rechercher (nom, ville, code postal) …">
            </div>
            <div class="field">
                <i class="bi bi-geo-alt"></i>
                <input type="text" placeholder="Ville…">
            </div>

            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
        <small class="filters-note">Astuce : exporte ta liste en CSV pour la réimporter après modification.</small>
    </section>

    <!-- Tableau + Panneau latéral -->
    <section class="layout">
        <!-- Liste magasins -->
        <div class="card">
            <div class="card-head">
                <h2>Magasins (3)</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-download"></i> Exporter</button>
                    <button class="btn ghost"><i class="bi bi-eye"></i> Aperçu public</button>
                </div>
            </div>

            <?php
            $dsn = 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8';
            $bdd = new PDO($dsn, 'root', '');
            $sqlMagasin = $bdd->prepare("SELECT * FROM magasins ");
            $sqlMagasin->execute();
            $lignesMagasins = $sqlMagasin->fetchAll();


            require_once "../../src/bdd/Bdd.php";
            use bdd\Bdd;

            $pdo = (new Bdd())->getBdd();

            // PAGINATION
            $limit  = 3;
            $page   = max(1, intval($_GET['page'] ?? 1));
            $offset = ($page - 1) * $limit;

            // Total des lignes
            $sqlCountMagasins = "SELECT COUNT(*) FROM magasins";
            $stmt = $pdo->prepare($sqlCountMagasins);
            $stmt->execute();
            $total = $stmt->fetchColumn();
            $totalPages = ceil($total / $limit);

            // Récupération des lignes paginées
            $sqlMagasin = $pdo->prepare("SELECT * FROM magasins LIMIT :limit OFFSET :offset");
            $sqlMagasin->bindValue(':limit', $limit, PDO::PARAM_INT);
            $sqlMagasin->bindValue(':offset', $offset, PDO::PARAM_INT);
            $sqlMagasin->execute();
            $lignesMagasins = $sqlMagasin->fetchAll();

            ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Ville</th>
                        <th>Horaires</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                        <th style="width:180px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lignesMagasins as $magasin): ?>
                        <tr>
                            <td>Paristanbul <?= $magasin['ville_magasin'] ?></td>
                            <td><?= $magasin['rue'] ?>, <?= $magasin['cp'] ?></td>
                            <td>Paristanbul <?= $magasin['ville_magasin'] ?></td>
                            <td>8h30–20h</td>
                            <td>07 49 82 61 33</td>
                            <td><a class="link" href="#"><i class="bi bi-pencil"></i> Éditer</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="table-foot">
                    <span><?= $page ?> / <?= $totalPages ?></span>
                    <div class="pager">
                        <?php if ($page > 1): ?>
                            <a class="btn ghost" href="?page=<?= $page - 1 ?>">‹</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a class="btn ghost" href="?page=<?= $page + 1 ?>">›</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
        <style>
            <style>
            .pagination {
                margin-top: 20px;
                text-align: center;
            }
            .pagination a {
                display: inline-block;
                margin: 0 5px;
                padding: 6px 12px;
                background-color: #eee;
                color: #333;
                text-decoration: none;
                border-radius: 4px;
            }
            .pagination a.active {
                background-color: #333;
                color: #fff;
                font-weight: bold;
            }
            .pagination a.prev,
            .pagination a.next {
                font-weight: bold;
            }

        </style>

        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

            // Récupération des valeurs du formulaire
            $ville = $_POST['ville'];
            $ville = strtoupper($ville);
            $adresse = $_POST['prenom'];
            $cp = $_POST['cp'];
            $rue_complete = $adresse." ".$cp ;
            $image = "";
            $tel = $_POST['num_telephone'];
            $role = $_POST['role'];

            // Préparation de la requête
            $sql = "INSERT INTO magasins (ville_magasin, rue, image, cp, num_tel, horaire_ouverture,
                      horaire_fermeture, jours_ouverture, video_magasin)
            (?,?,?,?,?,?,?,?,?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ville,$rue_complete,"",$cp,$tel,"","","",""]);

        }

        ?>
        <!-- Panneau latéral : Ajouter / Éditer -->
        <aside class="sidepanel card">
            <div class="card-head">
                <h2>Ajouter / Éditer un magasin</h2>
            </div>

            <form class="form">

                <label>
                    <span>Adresse</span>
                    <input type="text" name=adresse" placeholder="117 Avenue Pierre Sémard">
                </label>

                <div class="grid two">
                    <label>
                        <span>Code postal</span>
                        <input type="text" name="cp" placeholder="95400">
                    </label>
                    <label>
                        <span>Ville</span>
                        <input type="text" name="ville" placeholder="Villiers-le-Bel">
                    </label>
                </div>

                <label>
                    <span>Téléphone</span>
                    <input type="tel" name="num_telephone" placeholder="07 49 82 61 33">
                </label>



                <div class="hours">
                    <div class="hours-head">
                        <h3><i class="bi bi-calendar-week"></i> Horaires</h3>
                        <button type="button" class="btn xs ghost">Copier à toute la semaine</button>
                    </div>
                    <div class="hours-grid">
                        <div class="row"><strong>Lun</strong><input type="text" placeholder="8h30–20h"></div>
                        <div class="row"><strong>Mar</strong><input type="text" placeholder="8h30–20h"></div>
                        <div class="row"><strong>Mer</strong><input type="text" placeholder="8h30–20h"></div>
                        <div class="row"><strong>Jeu</strong><input type="text" placeholder="8h30–20h"></div>
                        <div class="row"><strong>Ven</strong><input type="text" placeholder="8h30–20h"></div>
                        <div class="row"><strong>Sam</strong><input type="text" placeholder="8h30–20h"></div>
                        <div class="row"><strong>Dim</strong><input type="text" placeholder="8h30–20h"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
                    <button type="submit" class="btn"><i class="bi bi-check2-circle"></i> Publier</button>
                </div>
            </form>
        </aside>
    </section>

    <footer class="footer">
        <small>© 2025 — Back-office Paristanbul • Nos magasins</small>
    </footer>
</main>
</body>
</html>
<?php
