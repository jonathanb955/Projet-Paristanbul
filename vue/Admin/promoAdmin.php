


<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

// ============================
// FILTRES & PAGINATION
// ============================
$search = $_GET['search'] ?? '';
$rayon  = $_GET['tri-par-rayon'] ?? '';
$type   = $_GET['tri-par-type'] ?? '';
$statut = $_GET['tri-par-statut'] ?? '';

$limit  = 3;
$page   = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// ============================
// Compter le total (pour pagination)
// ============================
$sqlCount = "SELECT COUNT(*) FROM promotions WHERE 1=1";
$paramsCount = [];

// Recherche
if (!empty($search)) {
    $sqlCount .= " AND (libelle LIKE :search OR rayon LIKE :search OR type LIKE :search OR valeur LIKE :search OR periode LIKE :search)";
    $paramsCount[':search'] = "%$search%";
}

// Rayon
if (!empty($rayon)) {
    $sqlCount .= " AND rayon = :rayon";
    $paramsCount[':rayon'] = $rayon;
}

// Type
if (!empty($type)) {
    $sqlCount .= " AND type = :type";
    $paramsCount[':type'] = $type;
}

// Statut
if (!empty($statut)) {
    $sqlCount .= " AND LOWER(statut) = LOWER(:statut)";
    $paramsCount[':statut'] = $statut;
}

$stmt = $pdo->prepare($sqlCount);
$stmt->execute($paramsCount);
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $limit);

// ============================
// Récupérer les promotions filtrées
// ============================
$sql = "SELECT libelle, rayon, type, valeur, periode, statut
        FROM promotions
        WHERE 1=1";
$params = [];

// Recherche
if (!empty($search)) {
    $sql .= " AND (libelle LIKE :search OR rayon LIKE :search OR type LIKE :search OR valeur LIKE :search OR periode LIKE :search)";
    $params[':search'] = "%$search%";
}

// Rayon
if (!empty($rayon)) {
    $sql .= " AND rayon = :rayon";
    $params[':rayon'] = $rayon;
}

// Type
if (!empty($type)) {
    $sql .= " AND type = :type";
    $params[':type'] = $type;
}

// Statut
if (!empty($statut)) {
    $sql .= " AND LOWER(statut) = LOWER(:statut)";
    $params[':statut'] = $statut;
}

// Pagination
$sql .= " ORDER BY id_promotion DESC LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

// Bind filtres
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}

// Bind pagination
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();
$lignesPromotion = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ============================
// Construire l’URL de pagination avec filtres
// ============================
$queryParams = $_GET;
unset($queryParams['page']);
$baseUrl = 'promoAdmin.php?' . http_build_query($queryParams);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Promotions</title>
    <link rel="stylesheet" href=../../assets/css/admin.css />
    <link rel="stylesheet" href=../../assets/css/promoAdmin.css>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        <div class="menu-title">Contenus</div>
        <a class="menu-item" href="../../vue/Admin/promoAdmin.php"><i class="bi bi-megaphone"></i><span>Promotions</span></a>

        <div class="menu-title">Administration</div>
        <a class="menu-item" href="../../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>

        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
    <div class="sidebar-footer">

    </div>
</aside>

<main class="main">
    <header class="topbar">
        <?php var_dump($_POST) ?>
        <h1>Promotions — Administration</h1>
        <div class="top-actions">
            <button class="btn ghost"><i class="bi bi-upload"></i> Import CSV</button>
            <button class="btn"><i class="bi bi-plus-circle"></i> Créer une promo</button>
        </div>
    </header>

    <!-- Filtres -->
    <section class="filters">
        <form class="filters-bar" action="promoAdmin.php" method="get">
            <div class="field">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Rechercher (libellé, rayon, type)…">
            </div>
            <div class="field select">
                <i class="bi bi-grid-1x2"></i>
                <select name="tri-par-rayon">
                    <option value="">Rayon (tous)</option>
                    <option <?= ($_GET['tri-par-rayon'] ?? '') === 'Fruits & Légumes' ? 'selected' : '' ?>>Fruits & Légumes</option>
                    <option <?= ($_GET['tri-par-rayon'] ?? '') === 'Produits frais' ? 'selected' : '' ?>>Produits frais</option>
                    <option <?= ($_GET['tri-par-rayon'] ?? '') === 'Produits secs' ? 'selected' : '' ?>>Produits secs</option>
                    <option <?= ($_GET['tri-par-rayon'] ?? '') === 'Boissons' ? 'selected' : '' ?>>Boissons</option>
                    <option <?= ($_GET['tri-par-rayon'] ?? '') === 'Hygiène' ? 'selected' : '' ?>>Hygiène</option>
                    <option <?= ($_GET['tri-par-rayon'] ?? '') === 'Surgelés' ? 'selected' : '' ?>>Surgelés</option>
                    <option <?= ($_GET['tri-par-rayon'] ?? '') === 'Emballages' ? 'selected' : '' ?>>Emballages</option>
                </select>
            </div>
            <div class="field select">
                <i class="bi bi-tags"></i>
                <select name="tri-par-type">
                    <option value="">Type (tous)</option>
                    <option <?= ($_GET['tri-par-type'] ?? '') === '-%' ? 'selected' : '' ?>>-%</option>
                    <option <?= ($_GET['tri-par-type'] ?? '') === '2+1' ? 'selected' : '' ?>>2+1</option>
                    <option <?= ($_GET['tri-par-type'] ?? '') === '-50% sur 2e' ? 'selected' : '' ?>>-50% sur 2e</option>
                    <option <?= ($_GET['tri-par-type'] ?? '') === 'Prix choc' ? 'selected' : '' ?>>Prix choc</option>
                </select>
            </div>
            <div class="field select">
                <i class="bi bi-lightbulb"></i>
                <select name="tri-par-statut">
                    <option value="">Statut</option>
                    <option <?= ($_GET['tri-par-statut'] ?? '') === 'Publiée' ? 'selected' : '' ?>>Publiée</option>
                    <option <?= ($_GET['tri-par-statut'] ?? '') === 'Brouillon' ? 'selected' : '' ?>>Brouillon</option>
                    <option <?= ($_GET['tri-par-statut'] ?? '') === 'Planifiée' ? 'selected' : '' ?>>Planifiée</option>
                    <option <?= ($_GET['tri-par-statut'] ?? '') === 'Expirée' ? 'selected' : '' ?>>Expirée</option>
                </select>
            </div>
            <button class="btn" type="submit"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
        <small class="filters-note">Astuce : duplique une promo existante pour gagner du temps.</small>
    </section>

    <!-- Liste + panneau -->
    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Promotions actives & à venir</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-download"></i> Exporter</button>
                    <button class="btn ghost"><i class="bi bi-calendar3"></i> Calendrier</button>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Libellé</th><th>Rayon</th><th>Type</th><th>Valeur</th><th>Période</th><th>Statut</th><th style="width:220px"></th>
                    </tr>
                    </thead>
                    <tbody>
                <?php foreach ($lignesPromotion as $promotion) : ?>

                     <tr>
                        <td><strong><?= htmlspecialchars($promotion['libelle']) ?></strong></td>
                        <td><?= htmlspecialchars($promotion['rayon']) ?></td>
                        <td><span class="pill blue"><i class="bi bi-percent"></i><?= htmlspecialchars($promotion['type']) ?></span></td>
                        <td><?= htmlspecialchars($promotion['valeur']) ?></td>
                        <td><?= htmlspecialchars($promotion['periode']) ?></td>
                        <td><span class="pill success"><i class="bi bi-check-circle"></i><?= htmlspecialchars($promotion['statut']) ?></span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-files"></i> Dupliquer</button>
                            <button class="btn xs"><i class="bi bi-toggle-off"></i> Dépublier</button>
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
        </div>


        <?php

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

            // Récupération des valeurs du formulaire
            $libelle = $_POST['libelle'];
            $rayon = $_POST['rayon'];
            $type = $_POST['type'];
            $valeur = $_POST['valeur'];
            $periode = $_POST['periode'];
            $statut = $_POST['statut'];

            // Préparation de la requête
            $sql = "INSERT INTO promotions (libelle, rayon, type, valeur, periode, statut) 
            VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([ $libelle, $rayon, $type, $valeur, $periode, $statut]);

        }

        ?>
        <!-- Panneau création/édition -->
        <aside class="sidepanel card">
            <div class="card-head"><h2>Créer / Éditer une promo</h2></div>
            <form method="POST" action="promoAdmin.php" class="form">
                <label ><span>Libellé</span><input type="text" name="libelle" placeholder="Ex : -20% sur les boissons gazeuses" required></label>
                <label><span>Rayon</span>
                    <select name="rayon" required>
                        <option>Fruits & Légumes</option><option>Produits frais</option>
                        <option>Produits secs</option><option>Boissons</option>
                        <option>Hygiène</option><option>Surgelés</option>
                        <option>Emballages</option>
                    </select>
                </label>
                <div class="grid two">
                    <label><span>Type</span>
                        <select name="type" required>
                            <option>-%</option><option>2+1</option>
                            <option>-50% sur 2e</option><option>Prix choc</option>
                        </select>
                    </label>
                    <label><span>Valeur</span><input type="text" name="valeur" placeholder="Ex : 20% / 2+1 / 1,99€" required></label>
                </div>
                <label><span>Période</span><input type="text" name="periode" placeholder="JJ/MM/AAAA → JJ/MM/AAAA" required></label>
                <div class="grid two">
                    <label><span>Statut</span>
                        <select name="statut" required>
                            <option>Publiée</option><option>Brouillon</option><option>Planifiée</option>
                        </select>
                    </label>
                </div>
                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
                    <button type="submit" class="btn"><i class="bi bi-check2-circle"></i> Publier</button>
                </div>
            </form>

        </aside>
    </section>

    <footer class="footer"><small>© 2025 — Back-office Paristanbul • Promotions</small></footer>
</main>
</body>
</html>

