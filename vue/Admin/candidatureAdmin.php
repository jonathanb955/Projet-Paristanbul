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

    // filtres
    $search = $_GET['search'] ?? '';
    $poste = $_GET['tri-par-poste'] ?? '';
    $ville = $_GET['tri-par-magasin'] ?? '';
    $statut = $_GET['tri-par-statut'] ?? '';

    // pagination
    $limit = 3;
    $page = max(1, intval($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;

    // 1️⃣ compter le nombre total
    $sqlCount = "SELECT COUNT(*) FROM candidatures c
             LEFT JOIN offres_emplois o ON c.ref_offre = o.id_offre
             WHERE 1=1";
    $paramsCount = [];

    if (!empty($search)) {
        $sqlCount .= " AND (c.nom LIKE :search OR c.prenom LIKE :search OR c.email LIKE :search OR o.titre_poste LIKE :search)";
        $paramsCount[':search'] = "%$search%";
    }
    if (!empty($poste)) {
        $sqlCount .= " AND o.titre_poste = :poste";
        $paramsCount[':poste'] = $poste;
    }
    if (!empty($ville)) {
        $sqlCount .= " AND o.ville = :ville";
        $paramsCount[':ville'] = $ville;
    }
    if (!empty($statut)) {
        $sqlCount .= " AND LOWER(c.statut) = LOWER(:statut)";
        $paramsCount[':statut'] = $statut;
    }

    $stmt = $pdo->prepare($sqlCount);
    $stmt->execute($paramsCount);
    $total = $stmt->fetchColumn();
    $totalPages = ceil($total / $limit);

    // 2️⃣ récupérer les candidatures
    $sql = "SELECT c.*, o.titre_poste, o.ville
        FROM candidatures c
        LEFT JOIN offres_emplois o ON c.ref_offre = o.id_offre
        WHERE 1=1";
    $params = $paramsCount;

    if (!empty($search)) {
        $sql .= " AND (c.nom LIKE :search OR c.prenom LIKE :search OR c.email LIKE :search OR o.titre_poste LIKE :search)";
    }
    if (!empty($poste)) {
        $sql .= " AND o.titre_poste = :poste";
    }
    if (!empty($ville)) {
        $sql .= " AND o.ville = :ville";
    }
    if (!empty($statut)) {
        $sql .= " AND LOWER(c.statut) = LOWER(:statut)";
    }

    // 3️⃣ injecter LIMIT et OFFSET directement dans la requête
    $sql .= " ORDER BY c.date_candidature DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

    $reqCandidatures = $pdo->prepare($sql);
    $reqCandidatures->execute($params);

    $lignesCandidatures = $reqCandidatures->fetchAll(PDO::FETCH_ASSOC);

    ?>





    <section class="filters">
        <form class="filters-bar" action="" method="get">
            <!-- Recherche -->
            <div class="field">
                <i class="bi bi-search"></i>
                <input type="search" name="search" placeholder="Rechercher (nom, email, poste)…" value="<?= htmlspecialchars($search) ?>">
            </div>

            <!-- Poste -->
            <div class="field select"><i class="bi bi-briefcase"></i>
                <select name="tri-par-poste">
                    <option value="">Poste (tous)</option>
                    <option <?= $poste === "Caissier(ère)" ? 'selected' : '' ?>>Caissier(ère)</option>
                    <option <?= $poste === "Préparateur(trice)" ? 'selected' : '' ?>>Préparateur(trice)</option>
                    <option <?= $poste === "Manager" ? 'selected' : '' ?>>Manager</option>
                </select>
            </div>

            <?php
            // Connexion PDO
            $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

            // Récupérer les villes distinctes depuis la table offres_emplois
            $stmtVilles = $pdo->query("SELECT DISTINCT ville_magasin FROM magasins ORDER BY ville_magasin ASC");
            $villes = $stmtVilles->fetchAll(PDO::FETCH_COLUMN);
            ?>
            <!-- Ville -->
            <div class="field select"><i class="bi bi-geo-alt"></i>
                <select name="tri-par-magasin">
                    <option value="">Magasin (tous)</option>
                    <?php foreach ($villes as $v) : ?>
                        <option value="<?= htmlspecialchars($v) ?>" <?= ($ville === $v ? 'selected' : '') ?>>
                            <?= htmlspecialchars($v) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Statut -->
            <div class="field select"><i class="bi bi-lightbulb"></i>
                <select name="tri-par-statut">
                    <option value="">Statut (tous)</option>
                    <option value="Nouveau" <?= $statut === "Nouveau" ? 'selected' : '' ?>>Nouveau</option>
                    <option value="En attente" <?= $statut === "En attente" ? 'selected' : '' ?>>En attente</option>
                    <option value="Retenu" <?= $statut === "Retenu" ? 'selected' : '' ?>>Retenu</option>
                    <option value="Refuse" <?= $statut === "Refuse" ? 'selected' : '' ?>>Refusé</option>
                    <option value="Archive" <?= $statut === "Archive" ? 'selected' : '' ?>>Archivé</option>
                </select>
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
                <table class="table" id="candidaturesTable">
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
                            <td><?= !empty($candidature['titre_poste']) ? htmlspecialchars($candidature['titre_poste']) : '<em>Candidature spontanée</em>' ?></td>
                            <td><?= !empty($candidature['ville']) ? htmlspecialchars($candidature['ville']) : '<em>Non précisée</em>' ?></td>
                             <?php
                            $statutCandidat = $candidature['statut'];
                            $classePill = 'warning'; // défaut

                            switch(strtolower($statutCandidat)) {
                                case 'nouveau':
                                case 'en attente':
                                    $classePill = 'warning';
                                    break;
                                case 'retenu':
                                    $classePill = 'success';
                                    break;
                                case 'refuse':
                                case 'refusé':
                                    $classePill = 'danger';
                                    break;
                                case 'archive':
                                case 'archivé':
                                    $classePill = 'secondary';
                                    break;
                            }
                            ?>
                            <td>
                            <span class="pill <?= $classePill ?>">
                                <i class="bi bi-star"></i> <?= htmlspecialchars($statutCandidat) ?>
                            </span>
                            </td>

                            <td>
                                <a class="link" href="<?= "vue/telechargement/".$candidature['lien_cv'] ?>" download>
                                    <i class="bi bi-file-earmark-text"></i> Télécharger CV
                                </a>
                            </td>                            <td class="row-actions">
                                <!-- Retenir -->
                                <form action="../../src/traitement/update_candidatures.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $candidature['id'] ?>">
                                    <input type="hidden" name="action" value="retenir">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check2-circle"></i> Retenir
                                    </button>
                                </form>

                                <!-- Refuser -->
                                <form action="../../src/traitement/update_candidatures.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $candidature['id'] ?>">
                                    <input type="hidden" name="action" value="refuser">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Refuser
                                    </button>
                                </form>

                                <!-- Archiver -->
                                <form action="../../src/traitement/update_candidatures.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $candidature['id'] ?>">
                                    <input type="hidden" name="action" value="archiver">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-archive"></i> Archiver
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const rows = document.querySelectorAll("#candidaturesTable tbody tr");
                console.log("Total lignes trouvées :", rows.length);
                const rowsPerPage = 3;
                let currentPage = 1;
                const totalPages = Math.ceil(rows.length / rowsPerPage);

                const pageInfo = document.getElementById("pageInfo");
                const prevBtn = document.getElementById("prevPage");
                const nextBtn = document.getElementById("nextPage");

                function showPage(page) {
                    rows.forEach((row, i) => {
                        row.style.display = (i >= (page - 1) * rowsPerPage && i < page * rowsPerPage) ? "" : "none";
                    });
                    pageInfo.textContent = `${page} / ${totalPages}`;
                    prevBtn.disabled = page === 1;
                    nextBtn.disabled = page === totalPages;
                }

                prevBtn.addEventListener("click", () => {
                    if (currentPage > 1) {
                        currentPage--;
                        showPage(currentPage);
                    }
                });

                nextBtn.addEventListener("click", () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        showPage(currentPage);
                    }
                });

                // Initialiser
                showPage(currentPage);
            });
        </script>
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
