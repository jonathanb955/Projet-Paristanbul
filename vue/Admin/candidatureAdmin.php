<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Candidatures</title>
    <link rel="stylesheet" href=../../assets/css/admin.css />
    <link rel="stylesheet" href=../../assets/css/candidatureAdmin.css />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<aside class="sidebar">
    <div class="brand">
        <a href="../../vue/index.php"><img src="../../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" /></a>
    </div>    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="../../vue/pageAdmin.php"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="menu-item" href="../../vue/Admin/nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Nos magasins</span></a>
        <div class="menu-title">Contenus</div>

        <a class="menu-item" href="../../vue/Admin/promoAdmin.php"><i class="bi bi-megaphone"></i><span>Promotions</span></a>
        <div class="menu-title">Administration</div>
        <a class="menu-item" href="../../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>

        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>

    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <h1>Candidatures — Administration</h1>
        <div class="top-actions">
            <button class="btn ghost"><i class="bi bi-archive"></i> Archiver lues</button>
            <button class="btn"><i class="bi bi-download"></i> Export CSV</button>
        </div>
    </header>

    <?php
    $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

    // Toutes les candidatures
   $reqCandidatures = $pdo->prepare("
    SELECT *
    FROM candidatures c
    INNER JOIN offres_emplois o ON c.ref_offre = o.id_offre
");
$reqCandidatures->execute();
$lignesCandidatures = $reqCandidatures->fetchAll();
foreach ($lignesCandidatures as $candidature) {
    $nom = $candidature['nom'];
    $prenom = $candidature['prenom'];
    $email = $candidature['email'];
    $telephone = $candidature['telephone'];
    $poste = $candidature['titre_poste'];
    $ville = $candidature['ville'];

    }

?>


    <section class="filters">
        <form class="filters-bar" action="" method="get">
            <div class="field"><i class="bi bi-search"></i><input type="search" placeholder="Rechercher (nom, email, poste)…"></div>
            <div class="field select"><i class="bi bi-briefcase"></i>
                <select name ="tri-par-poste"><option value="">Poste (tous)</option><option>Caissier(ère)</option><option>Préparateur(trice)</option><option>Manager</option></select>
            </div>
            <div class="field select"><i class="bi bi-geo-alt"></i>
                <select name="trie-par-magasin"><option value="">Magasin (tous)</option><option>Villiers-le-Bel</option><option>Bondy</option><option>Drancy</option></select>
            </div>
            <div class="field select"><i class="bi bi-lightbulb"></i>
                <select><option value="">Statut</option><option>Nouveau</option><option>Retenu</option><option>Refusé</option><option>Archivé</option></select>
            </div>
            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>

    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Liste des candidatures</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-eye"></i> Aperçu formulaire public</button>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom</th><th>Email</th><th>Tél.</th><th>Poste</th><th>Magasin</th><th>Statut</th><th>CV</th><th style="width:240px"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($lignesCandidatures as $candidature) : ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($candidature['prenom'].' '.$candidature['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($candidature['email']) ?></td>
                            <td><?= htmlspecialchars($candidature['telephone']) ?></td>
                            <td><?= htmlspecialchars($candidature['titre_poste']) ?></td>
                            <td><?= htmlspecialchars($candidature['ville']) ?></td>
                            <td><span class="pill warning"><i class="bi bi-star"></i> Nouveau</span></td>
                            <td><a class="link" href="<?= "vue/telechargement/".$candidature['lien_cv'] ?>" download><i class="bi bi-file-earmark-text"></i> Télécharger CV</a></td>';

                            <td class="row-actions">
                                <button class="btn xs ghost"><i class="bi bi-eye"></i> Voir</button>
                                <button class="btn xs"><i class="bi bi-check2-circle"></i> Retenir</button>
                                <button class="btn xs ghost"><i class="bi bi-x-circle"></i> Refuser</button>
                                <button class="btn xs ghost"><i class="bi bi-archive"></i> Archiver</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-foot">
                <span>3 / 3</span>
                <div class="pager"><button class="btn ghost">‹</button><button class="btn ghost">›</button></div>
            </div>
        </div>
        <br>
        <br>
        <?php
        $dsn = 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8';
        $bdd = new PDO($dsn, 'root', '');

        // formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $secteur_activite = $_POST['secteur_activite'];
            $titre_poste      = $_POST['titre_poste'];
            $ville            = $_POST['ville'];
            $departement      = $_POST['departement'];
            $type_contrat     = $_POST['type_contrat'];
            $detail_poste     = $_POST['detail_poste'];

            $sql = $bdd->prepare("
        INSERT INTO offres_emplois 
        (secteur_activite, titre_poste, ville, departement, type_contrat, detail_poste)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
            $sql->execute([$secteur_activite, $titre_poste, $ville, $departement, $type_contrat, $detail_poste]);

            echo "<p style='color:green;'>Offre d'emploi enregistrée avec succès !</p>";
        }

        ?>
        <!-- Panneau Offre -->
        <aside class="sidepanel card">
            <div class="card-head"><h2>Créer une offre d'emploi</h2></div>
            <form class="form" action="candidatureAdmin.php" method="post">
                <!-- Secteur d'activité -->
                <label>
                    <span>Secteur d'activité</span>
                    <input type="text" name="secteur_activite" placeholder="Ex : Commerce, Restauration" required>
                </label>

                <!-- Titre du poste -->
                <label>
                    <span>Titre du poste</span>
                    <input type="text" name="titre_poste" placeholder="Ex : Caissier(ère)" required>
                </label>

                <?php
                $dsn = 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8';
                $bdd = new PDO($dsn, 'root', '');
                $sqlMagasin = $bdd->prepare("SELECT * FROM magasins ");
                $sqlMagasin->execute();
                $lignesMagasins = $sqlMagasin->fetchAll();
                ?>
                <!-- Ville -->
                <label>
                    <span>Villes</span>
                    <select name="ville" required>
                        <?php foreach($lignesMagasins as $magasin): ?>
                            <option value="<?= $magasin['ville_magasin'] ?>"><?= $magasin['ville_magasin'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </label>

                <!-- Département -->
                <label>
                    <span>Département</span>
                    <input type="text" name="departement" placeholder="Ex : 95" required>
                </label>

                <!-- Type de contrat -->
                <label>
                    <span>Type de contrat</span>
                    <select name="type_contrat" required>
                        <option value="CDD">CDD</option>
                        <option value="CDI">CDI</option>
                        <option value="Stage">Stage</option>
                        <option value="Alternance">Alternance</option>
                    </select>
                </label>

                <!-- Détails du poste -->
                <label>
                    <span>Détails du poste</span>
                    <textarea name="detail_poste" rows="4" placeholder="Description du poste..." required></textarea>
                </label>

                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
                    <button type="submit" class="btn"><i class="bi bi-send"></i> Enregistrer</button>
                </div>
            </form>
        </aside>
    </section>

    <footer class="footer"><small>© 2025 — Back-office Paristanbul • Candidatures</small></footer>
</main>
</body>
</html>
