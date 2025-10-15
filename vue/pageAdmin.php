<?php
require_once "../src/bdd/Bdd.php";
use bdd\Bdd;

// Connexion à la base
$pdo = (new Bdd())->getBdd();

// === INDICATEURS PRINCIPAUX ===

// 1️⃣ Total candidatures
$totalCandidatures = $pdo->query("SELECT COUNT(*) FROM candidatures")->fetchColumn();

// 2️⃣ Offres actives
$totalOffres = $pdo->query("SELECT COUNT(*) FROM offres_emplois")->fetchColumn();

// 3️⃣ Candidatures en attente
$totalEnAttente = $pdo->query("SELECT COUNT(*) FROM candidatures WHERE statut = 'En attente'")->fetchColumn();

// 4️⃣ Candidatures déposées cette semaine
$sqlRecentes = "SELECT COUNT(*) FROM candidatures WHERE YEARWEEK(date_candidature, 1) = YEARWEEK(NOW(), 1)";
$candidaturesRecentes = $pdo->query($sqlRecentes)->fetchColumn();


// === PAGINATION MAGASINS ===
$limit  = 3;
$page   = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Compter le total des magasins
$sqlCountMagasins = "SELECT COUNT(*) FROM magasins";
$stmt = $pdo->prepare($sqlCountMagasins);
$stmt->execute();
$total = $stmt->fetchColumn();
$totalPages = ceil($total / $limit);

// Récupérer les magasins paginés
$sqlMagasin = $pdo->prepare("SELECT * FROM magasins LIMIT :limit OFFSET :offset");
$sqlMagasin->bindValue(':limit', $limit, PDO::PARAM_INT);
$sqlMagasin->bindValue(':offset', $offset, PDO::PARAM_INT);
$sqlMagasin->execute();
$lignesMagasins = $sqlMagasin->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="brand">
        <a href="../vue/index.php"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" /></a>
    </div>

    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item active" href="../vue/pageAdmin.php"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="menu-item" href="../vue/Admin/nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Nos magasins</span></a>

        <div class="menu-title">Administration</div>
        <a class="menu-item" href="../vue/Admin/gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
        <a class="menu-item" href="../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>
        <a class="menu-item" href="../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>

    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Se déconnecter</a>
    </div>
</aside>

<!-- Main -->
<main class="main">
    <!-- Topbar -->
    <header class="topbar">
        <div class="left">
            <div class="store-switcher">
                <i class="bi bi-building"></i>
                <select>
                    <option>Villiers-le-Bel</option>
                    <option>Sarcelles</option>
                    <option>Garges</option>
                    <option>Bondy</option>
                    <option>Drancy</option>
                </select>
            </div>
        </div>

        <form class="search">
            <i class="bi bi-search"></i>
            <input type="search" placeholder="Rechercher un produit, un rayon, une promo, un message…" />
        </form>

        <div class="top-actions">
            <button class="btn ghost"><i class="bi bi-bell"></i></button>
            <button class="btn"><i class="bi bi-plus-lg"></i> Nouvelle promo</button>
        </div>
    </header>

    <!-- KPI -->
    <section class="grid kpis">
        <div class="card kpi">
            <div class="kpi-top">
                <span class="kpi-label">Total candidatures</span>
                <span class="badge up"><i class="bi bi-graph-up"></i></span>
            </div>
            <div class="kpi-main"><?= $totalCandidatures ?></div>
            <div class="kpi-foot">Candidatures reçues au total</div>
        </div>

        <div class="card kpi">
            <div class="kpi-top">
                <span class="kpi-label">Nombre d’offres actives</span>
                <span class="badge flat"><i class="bi bi-dash"></i></span>
            </div>
            <div class="kpi-main"><?= $totalOffres ?></div>
            <div class="kpi-foot">Offres actuellement publiées</div>
        </div>

        <div class="card kpi">
            <div class="kpi-top">
                <span class="kpi-label">Abonnés à la newsletter</span>
                <span class="badge down"><i class="bi bi-hourglass-split"></i></span>
            </div>
            <div class="kpi-main">0</div>
            <div class="kpi-foot">En cours de traitement</div>
        </div>

        <div class="card kpi">
            <div class="kpi-top">
                <span class="kpi-label">Candidatures cette semaine</span>
                <span class="badge up"><i class="bi bi-calendar-week"></i></span>
            </div>
            <div class="kpi-main"><?= $candidaturesRecentes ?></div>
            <div class="kpi-foot">Déposées cette semaine</div>
        </div>
    </section>

    <!-- Gestion magasins -->
    <section class="grid two">
        <div class="card">
            <div class="card-head">
                <h2>Magasins</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-download"></i> Exporter</button>
                    <button class="btn"><i class="bi bi-plus-circle"></i> Ajouter un magasin</button>
                </div>
            </div>

            <div class="table-wrap" id="tableau">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Horaires</th>
                        <th>Tél.</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lignesMagasins as $magasin): ?>
                        <tr>
                            <td>Paristanbul <?= htmlspecialchars($magasin['ville_magasin']) ?></td>
                            <td><?= htmlspecialchars($magasin['rue']) ?>, <?= htmlspecialchars($magasin['cp']) ?></td>
                            <td><?= htmlspecialchars($magasin['horaire_ouverture']) ?>–<?= htmlspecialchars($magasin['horaire_fermeture']) ?></td>
                            <td><?= htmlspecialchars($magasin['num_tel']) ?></td>
                            <td><a class="link" href="#"><i class="bi bi-pencil"></i> Éditer</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="table-foot">
                <span><?= $page ?> / <?= $totalPages ?></span>
                <div class="pager">
                    <?php if ($page > 1): ?>
                        <a class="btn ghost" href="?page=<?= $page - 1 ?>#tableau">‹</a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                        <a class="btn ghost" href="?page=<?= $page + 1 ?>#tableau">›</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Messages contact & candidatures -->
        <div class="card">
            <div class="card-head">
                <h2>Messages & Candidatures</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-archive"></i> Archiver lus</button>
                </div>
            </div>

            <div class="inbox">
                <div class="inbox-item">
                    <div class="inbox-left">
                        <span class="pill blue"><i class="bi bi-envelope"></i> Contact</span>
                        <strong>Demande infos promotions</strong>
                        <small>aujourd’hui • contact@exemple.fr</small>
                    </div>
                    <div class="inbox-right">
                        <button class="btn ghost"><i class="bi bi-reply"></i> Répondre</button>
                        <button class="btn ghost"><i class="bi bi-check2"></i> Marquer lu</button>
                    </div>
                </div>
                <!-- autres messages -->
            </div>
        </div>
    </section>

    <footer class="footer">
        <small>© 2025 — Back-office Paristanbul. HTML + CSS + Bootstrap Icons.</small>
    </footer>
</main>
</body>
</html>
