<?php

require_once "../../src/bdd/Bdd.php";
$bddObj = new \bdd\Bdd();
$pdo = $bddObj->getBdd();

// Lecture des critères de filtre
$search = $_GET['search'] ?? '';
$poste  = $_GET['tri-par-poste'] ?? '';
$ville  = $_GET['tri-par-magasin'] ?? '';
$statut = $_GET['tri-par-statut'] ?? '';

$limit  = 3;
$page   = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Construction de la requête COUNT pour pagination
$sqlCount = "SELECT COUNT(*) FROM candidatures c
             LEFT JOIN offres_emplois o ON c.ref_offre = o.id_offre
             WHERE 1=1";

$paramsCount = [];

if (!empty($search)) {
    $sqlCount .= " AND (
        LOWER(c.nom) LIKE LOWER(:search)
        OR LOWER(c.prenom) LIKE LOWER(:search)
        OR LOWER(c.email) LIKE LOWER(:search)
        OR LOWER(o.titre_poste) LIKE LOWER(:search)
    )";
    $paramsCount[':search'] = "%$search%";
}

if (!empty($poste)) {
    if ($poste === 'Candidature spontanée') {
        $sqlCount .= " AND c.ref_offre IS NULL";
    } else {
        $sqlCount .= " AND o.titre_poste = :poste";
        $paramsCount[':poste'] = $poste;
    }
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

// Requête principale pour récupérer les candidatures avec filtres et pagination
$sql = "SELECT c.*, o.titre_poste, o.ville
        FROM candidatures c
        LEFT JOIN offres_emplois o ON c.ref_offre = o.id_offre
        WHERE 1=1";

$params = [];

if (!empty($search)) {
    $sql .= " AND (
        LOWER(c.nom) LIKE LOWER(:search)
        OR LOWER(c.prenom) LIKE LOWER(:search)
        OR LOWER(c.email) LIKE LOWER(:search)
        OR LOWER(o.titre_poste) LIKE LOWER(:search)
        OR LOWER(o.ville) LIKE LOWER(:search)
    )";
    $params[':search'] = "%$search%";
}

if ($poste !== '') {
    if ($poste === 'Candidature spontanée') {
        $sql .= " AND c.ref_offre IS NULL";
    } else {
        $sql .= " AND o.titre_poste = :poste";
        $params[':poste'] = $poste;
    }
}

if (!empty($ville)) {
    $sql .= " AND o.ville = :ville";
    $params[':ville'] = $ville;
}

if (!empty($statut)) {
    $sql .= " AND LOWER(c.statut) = LOWER(:statut)";
    $params[':statut'] = $statut;
}

$sql .= " ORDER BY c.id ASC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

$stmt->execute();
$lignesCandidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les villes distinctes pour le filtre
$stmtVilles = $pdo->query("SELECT DISTINCT ville_magasin FROM magasins ORDER BY ville_magasin ASC");
$villes = $stmtVilles->fetchAll(PDO::FETCH_COLUMN);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Candidatures</title>
    <link rel="stylesheet" href="../../assets/css/admin.css" />
    <link rel="stylesheet" href="../../assets/css/candidatureAdmin.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body>

<!-- Sidebar (barre de gauche) -->
<aside class="sidebar">
    <div class="brand">
        <a href="../../vue/index.php">
            <img src="../../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" />
        </a>
    </div>
    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="../../vue/pageAdmin.php"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="menu-item" href="../../vue/Admin/nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Nos magasins</span></a>

        <div class="menu-title">Administration</div>
        <a class="menu-item" href="gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<!-- Main content -->
<main class="main">
    <!-- Header -->
    <header class="topbar">
        <h1>Candidatures — Administration</h1>
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

    <!-- Barre de filtres et recherche -->
    <section class="filters">
        <form class="filters-bar" action="" method="get">
            <div class="field">
                <i class="bi bi-search"></i>
                <input
                        type="search"
                        name="search"
                        placeholder="Rechercher (nom, email, poste)…"
                        value="<?= htmlspecialchars($search) ?>"
                />
            </div>

            <div class="field select"><i class="bi bi-briefcase"></i>
                <select name="tri-par-poste">
                    <option value="">Poste (tous)</option>
                    <option <?= $poste === "Caissier(ère)" ? 'selected' : '' ?>>Caissier(ère)</option>
                    <option <?= $poste === "Préparateur(trice)" ? 'selected' : '' ?>>Préparateur(trice)</option>
                    <option <?= $poste === "Manager" ? 'selected' : '' ?>>Manager</option>
                    <option <?= $poste === "Manutentionnaire" ? 'selected' : '' ?>>Manutentionnaire</option>
                    <option value="Candidature spontanée" <?= $poste === "Candidature spontanée" ? 'selected' : '' ?>>Candidature spontanée</option>
                </select>
            </div>

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

    <!-- Liste des candidatures -->
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
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Tél.</th>
                        <th>Poste</th>
                        <th>Magasin</th>
                        <th>Statut</th>
                        <th>Actions</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lignesCandidatures as $candidature) : ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($candidature['prenom'] . ' ' . $candidature['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($candidature['email']) ?></td>
                            <td><?= htmlspecialchars($candidature['telephone']) ?></td>
                            <td><?= !empty($candidature['titre_poste']) ? htmlspecialchars($candidature['titre_poste']) : '<em>Candidature spontanée</em>' ?></td>
                            <td><?= !empty($candidature['ville']) ? htmlspecialchars($candidature['ville']) : '<em>Non précisée</em>' ?></td>
                            <?php
                            $statutCandidat = $candidature['statut'];
                            $classePill = 'warning'; // défaut
                            switch (strtolower($statutCandidat)) {
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

                            <td class="row-actions">
                                <form action="../../src/traitement/update_candidatures.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $candidature['id'] ?>">
                                    <input type="hidden" name="action" value="retenir">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-check2-circle"></i> Retenir
                                    </button>
                                </form>
                                <form action="../../src/traitement/update_candidatures.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $candidature['id'] ?>">
                                    <input type="hidden" name="action" value="refuser">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-circle"></i> Refuser
                                    </button>
                                </form>
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

                <!-- Pagination -->
                <div class="table-foot">
                    <span><?= $page ?> / <?= $totalPages ?></span>
                    <div class="pager">
                        <?php
                        // Construire la query string en gardant les filtres actuels
                        $queryParams = $_GET;
                        ?>
                        <?php if ($page > 1) :
                            $queryParams['page'] = $page - 1;
                            $prevUrl = '?' . http_build_query($queryParams);
                            ?>
                            <a class="btn ghost" href="<?= $prevUrl ?>">‹</a>
                        <?php endif; ?>

                        <?php if ($page < $totalPages) :
                            $queryParams['page'] = $page + 1;
                            $nextUrl = '?' . http_build_query($queryParams);
                            ?>
                            <a class="btn ghost" href="<?= $nextUrl ?>">›</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <small>© 2025 — Back-office Paristanbul • Candidatures</small>
    </footer>
</main>

</body>
</html>
