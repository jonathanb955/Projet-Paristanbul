<?php
require_once "../../src/bdd/Bdd.php";

use bdd\Bdd;

$bddObj = new Bdd();
$pdo = $bddObj->getBdd();

// 🔍 Filtres
$search = $_GET['search'] ?? '';
$ville  = $_GET['tri-par-ville'] ?? '';

$limit  = 5;
$page   = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// 🧮 Nombre total pour pagination
$sqlCount = "SELECT COUNT(*) FROM magasins WHERE 1=1";
$paramsCount = [];

if (!empty($search)) {
    $sqlCount .= " AND (
        LOWER(ville_magasin) LIKE LOWER(:search)
        OR LOWER(rue) LIKE LOWER(:search)
        OR LOWER(cp) LIKE LOWER(:search)
        OR LOWER(num_tel) LIKE LOWER(:search)
    )";
    $paramsCount[':search'] = "%$search%";
}

if (!empty($ville)) {
    $sqlCount .= " AND ville_magasin = :ville";
    $paramsCount[':ville'] = $ville;
}

$stmt = $pdo->prepare($sqlCount);
$stmt->execute($paramsCount);
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $limit);

// 🏪 Requête principale
$sql = "SELECT * FROM magasins WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (
        LOWER(ville_magasin) LIKE LOWER(:search)
        OR LOWER(rue) LIKE LOWER(:search)
        OR LOWER(cp) LIKE LOWER(:search)
        OR LOWER(num_tel) LIKE LOWER(:search)
    )";
    $params[':search'] = "%$search%";
}

if (!empty($ville)) {
    $sql .= " AND ville_magasin = :ville";
    $params[':ville'] = $ville;
}

$sql .= " ORDER BY id_magasin ASC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

$stmt->execute();
$magasins = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🏙️ Liste des villes pour le filtre
$stmtVilles = $pdo->query("SELECT DISTINCT ville_magasin FROM magasins ORDER BY ville_magasin ASC");
$villes = $stmtVilles->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Magasins</title>
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
        <a class="menu-item" href="gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
        <a class="menu-item" href="candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>
        <a class="menu-item" href="gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<!-- Contenu principal -->
<main class="main">
    <header class="topbar">
        <h1>Nos Magasins — Administration</h1>
        <div class="top-actions">
            <a href="ajouterMagasinAdmin.php" class="btn"><i class="bi bi-plus-circle"></i> Ajouter un magasin</a>
        </div>
    </header>

    <!-- Filtres -->
    <section class="filters">
        <form class="filters-bar" action="" method="get">
            <div class="field">
                <i class="bi bi-search"></i>
                <input type="search" name="search" placeholder="Rechercher (ville, rue...)" value="<?= htmlspecialchars($search) ?>" />
            </div>

            <div class="field select">
                <i class="bi bi-geo-alt"></i>
                <select name="tri-par-ville">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $v): ?>
                        <option value="<?= htmlspecialchars($v) ?>" <?= $ville === $v ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>

    <!-- Tableau -->
    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Liste des magasins</h2>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ville</th>
                        <th>Rue</th>
                        <th>Code postal</th>
                        <th>Téléphone</th>
                        <th>Horaires</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($magasins)): ?>
                        <tr><td colspan="7" style="text-align:center;"><em>Aucun magasin trouvé</em></td></tr>
                    <?php else: ?>
                        <?php foreach ($magasins as $magasin): ?>
                            <tr>
                                <td><?= htmlspecialchars($magasin['id_magasin']) ?></td>
                                <td><?= htmlspecialchars($magasin['ville_magasin']) ?></td>
                                <td><?= htmlspecialchars($magasin['rue']) ?></td>
                                <td><?= htmlspecialchars($magasin['cp']) ?></td>
                                <td><?= htmlspecialchars($magasin['num_tel']) ?></td>
                                <td>
                                    <?= htmlspecialchars(substr($magasin['horaire_ouverture'], 0, 5)) ?> —
                                    <?= htmlspecialchars(substr($magasin['horaire_fermeture'], 0, 5)) ?>
                                </td>
                                <td class="row-actions">
                                    <a href="../../src/traitement/editMagasin.php?id=<?= $magasin['id_magasin'] ?>" class="btn btn-sm btn-outline"><i class="bi bi-pencil"></i> Modifier</a>
                                    <form action="../../src/traitement/supprimer_magasin.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $magasin['id_magasin'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce magasin ?')">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="table-foot">
                    <span><?= $page ?> / <?= $totalPages ?></span>
                    <div class="pager">
                        <?php
                        $queryParams = $_GET;
                        if ($page > 1) {
                            $queryParams['page'] = $page - 1;
                            echo '<a class="btn ghost" href="?' . http_build_query($queryParams) . '">‹</a>';
                        }
                        if ($page < $totalPages) {
                            $queryParams['page'] = $page + 1;
                            echo '<a class="btn ghost" href="?' . http_build_query($queryParams) . '">›</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <footer class="footer">
        <small>© 2025 — Back-office Paristanbul • Magasins</small>
    </footer>
</main>

</body>
</html>
