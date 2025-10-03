<?php

$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ============================
// FILTRES & PAGINATION
// ============================
$search = $_GET['search'] ?? '';
$poste  = $_GET['tri-par-poste'] ?? '';
$ville  = $_GET['tri-par-magasin'] ?? '';

$limit  = 3;
$page   = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// ========== 1. Compter le total pour la pagination ==========
$sqlCount = "SELECT COUNT(*) FROM offres_emplois WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sqlCount .= " AND (titre_poste LIKE :search OR secteur_activite LIKE :search OR detail_poste LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}
if (!empty($poste)) {
    $sqlCount .= " AND titre_poste = :poste";
    $params[':poste'] = $poste;
}
if (!empty($ville)) {
    $sqlCount .= " AND ville = :ville";
    $params[':ville'] = $ville;
}

$stmt = $pdo->prepare($sqlCount);
$stmt->execute($params);
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $limit);

// ========== 2. Récupérer les résultats paginés =============
$sql = "SELECT * FROM offres_emplois WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (titre_poste LIKE :search OR secteur_activite LIKE :search OR detail_poste LIKE :search)";
}
if (!empty($poste)) {
    $sql .= " AND titre_poste = :poste";
}
if (!empty($ville)) {
    $sql .= " AND ville = :ville";
}

$sql .= " ORDER BY id_offre DESC LIMIT :limit OFFSET :offset";

$params[':limit'] = $limit;
$params[':offset'] = $offset;

$stmt = $pdo->prepare($sql);

// Il faut binder limit/offset en tant qu'entiers
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

// Les autres paramètres (search, poste, ville)
if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
if (!empty($poste)) {
    $stmt->bindValue(':poste', $poste, PDO::PARAM_STR);
}
if (!empty($ville)) {
    $stmt->bindValue(':ville', $ville, PDO::PARAM_STR);
}

$stmt->execute();
$lignesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Offres d'emplois</title>
    <link rel="stylesheet" href=../../assets/css/admin.css />
    <link rel="stylesheet" href=../../assets/css/gestionOffreAdmin.css />
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
        <a class="menu-item" href="candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>

        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>

    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <h1>Offres — Administration</h1>
        <div class="top-actions">
            <form method="post" style="display:inline;">
                <button type="submit" name="archiver_lues" class="btn ghost">
                    <i class="bi bi-archive"></i> Archiver lues
                </button>
            </form>

            <form method="post" action="../../src/traitement/export_candidatures.php" style="display:inline;">
                <button type="submit" name="export_csv" class="btn">
                    <i class="bi bi-download"></i> Export CSV
                </button>
            </form>
        </div>
    </header>






    <section class="filters">
        <form class="filters-bar" action="" method="get">
            <!-- Recherche -->
            <div class="field">
                <i class="bi bi-search"></i>
                <input type="search" name="search" placeholder="Rechercher (nom, ville, code postal) …">
            </div>

            <!-- Poste -->
            <div class="field select"><i class="bi bi-briefcase"></i>
                <select name="tri-par-poste">
                    <option value="">Poste (tous)</option>
                    <option value="Caissier(ère)" <?= $poste === "Caissier(ère)" ? 'selected' : '' ?>>Caissier(ère)</option>
                    <option value="Préparateur de commande" <?= $poste === "Préparateur de commande" ? 'selected' : '' ?>>Préparateur de commande</option>
                    <option value="Comptable" <?= $poste === "Comptable" ? 'selected' : '' ?>>Comptable</option>
                    <option value="Manutentionnaire" <?= $poste === "Manutentionnaire" ? 'selected' : '' ?>>Manutentionnaire</option>
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
                        <option value="<?= htmlspecialchars($v) ?>" <?= $ville === $v ? 'selected' : '' ?>><?= htmlspecialchars($v) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>

    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Liste des offres d'emplois</h2>

            </div>

            <div class="table-wrap">
                <table class="table" id="candidaturesTable">
                    <thead>
                    <tr>
                        <th>Poste</th><th>Secteur d'activité</th><th>Contrat</th><th>Magasin</th><th style="width:240px"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($lignesOffres as $offre) : ?>

                        <tr>
                            <td><strong><?= htmlspecialchars($offre['titre_poste']) ?></strong></td>
                            <td><?= htmlspecialchars($offre['secteur_activite']) ?></td>
                            <td><?= htmlspecialchars($offre['type_contrat']) ?></td>
                            <td><?= htmlspecialchars($offre['ville']) ?></td>



                                               <td class="row-actions">
                                <!-- Modifier -->
                            <form action="../../src/traitement/update_offresAdmin.php" method="post" style="display:inline;" onsubmit="return confirm('Confirmer la suppression de cette offre ?');">
                              <input type="hidden" name="id" value="<?= $offre['id_offre'] ?>">
                              <input type="hidden" name="action" value="supprimer">
                              <button type="submit" class="btn btn-sm btn-danger">
                              <i class="bi bi-trash"></i> Supprimer
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
                    const rows = document.querySelectorAll("#table tbody tr");
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
                <form class="form" action="gestionOffreAdmin.php" method="post">
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
