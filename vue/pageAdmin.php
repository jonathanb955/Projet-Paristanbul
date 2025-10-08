<?php

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


        <div class="menu-title">Contenus</div>
        <a class="menu-item" href="../vue/Admin/promoAdmin.php"><i class="bi bi-megaphone"></i><span>Promotions</span></a>

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
                <span class="kpi-label">CA (estimation du jour)</span>
                <span class="badge up"><i class="bi bi-graph-up"></i> +8%</span>
            </div>
            <div class="kpi-main">7 420 €</div>
            <div class="kpi-foot">Panier moyen 11,9 €</div>
        </div>

        <div class="card kpi">
            <div class="kpi-top">
                <span class="kpi-label">Tickets</span>
                <span class="badge flat"><i class="bi bi-dash"></i></span>
            </div>
            <div class="kpi-main">623</div>
            <div class="kpi-foot">vs hier 618</div>
        </div>

        <div class="card kpi">
            <div class="kpi-top">
                <span class="kpi-label">Produits en rupture</span>
                <span class="badge down"><i class="bi bi-arrow-down"></i> -5</span>
            </div>
            <div class="kpi-main">14</div>
            <div class="kpi-foot">Top rayon : Frais</div>
        </div>

        <div class="card kpi">
            <div class="kpi-top">
                <span class="kpi-label">Candidatures (non lues)</span>
                <span class="badge up"><i class="bi bi-inbox"></i></span>
            </div>
            <div class="kpi-main">7</div>
            <div class="kpi-foot">Postuler</div>
        </div>
    </section>


        <!-- Form promos + activités -->
        <div class="card">
            <div class="card-head">
                <h2>Créer une promotion</h2>
            </div>
            <form class="form">
                <label>
                    <span>Rayon</span>
                    <select>
                        <option>Fruits & Légumes</option>
                        <option>Produits frais</option>
                        <option>Produits secs</option>
                        <option>Boissons</option>
                        <option>Hygiène</option>
                        <option>Surgelés</option>
                        <option>Emballages</option>
                    </select>
                </label>
                <label><span>Type</span>
                    <select name="type" required>
                        <option>-%</option><option>2+1</option>
                        <option>-50% sur 2e</option><option>Prix choc</option>
                    </select>
                </label>

                    <span>Taux de remise (%)</span>
                    <input type="number" min="1" max="90" placeholder="20" />
                </label>

                    <span>Période</span>
                    <input type="text" placeholder="03/09/2025 → 10/09/2025" />
                </label>
                <label>
                    <span>Libellé</span>
                    <input type="text" placeholder="Ex : -30% sur les fruits de saison" />
                </label>
                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
                    <button type="submit" class="btn"><i class="bi bi-megaphone"></i> Publier</button>
                </div>
            </form>

            <hr class="sep" />

            <h3>Activité récente</h3>
            <ul class="timeline">
                <li><span class="dot"></span><strong>03/09</strong> — Promo “Fruits -30%” créée</li>
                <li><span class="dot"></span><strong>02/09</strong> — 2 ruptures résolues (Épicerie)</li>
                <li><span class="dot"></span><strong>01/09</strong> — 120 tarifs mis à jour</li>
            </ul>
        </div>
    </section>

    <?php

    require_once "../src/bdd/Bdd.php";
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

    <!-- Gestion magasins / messages -->
    <section class="grid two">
        <!-- Magasins -->
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
                            <td>Paristanbul <?= $magasin['ville_magasin'] ?></td>
                            <td><?= $magasin['rue'] ?>, <?= $magasin['cp'] ?></td>
                            <td>8h30–20h</td>
                            <td>07 49 82 61 33</td>
                            <td><a class="link" href="#"><i class="bi bi-pencil"></i> Éditer</a></td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>
                    <!-- Pagination (placée ici, hors de la table) -->

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

                <div class="inbox-item">
                    <div class="inbox-left">
                        <span class="pill green"><i class="bi bi-briefcase"></i> Candidature</span>
                        <strong>Préparateur de commandes</strong>
                        <small>hier • cv.kadi@example.com</small>
                    </div>
                    <div class="inbox-right">
                        <button class="btn ghost"><i class="bi bi-eye"></i> Voir CV</button>
                        <button class="btn ghost"><i class="bi bi-check2"></i> Marquer lu</button>
                    </div>
                </div>

                <div class="inbox-item">
                    <div class="inbox-left">
                        <span class="pill blue"><i class="bi bi-envelope"></i> Contact</span>
                        <strong>Problème application</strong>
                        <small>02/09 • user@app.fr</small>
                    </div>
                    <div class="inbox-right">
                        <button class="btn ghost"><i class="bi bi-reply"></i> Répondre</button>
                        <button class="btn ghost"><i class="bi bi-check2"></i> Marquer lu</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <small>© 2025 — Back-office Paristanbul. HTML + CSS + Bootstrap Icons.</small>
    </footer>
</main>
</body>
</html>
