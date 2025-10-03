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

    <!-- 2 colonnes -->
    <section class="grid two">
        <!-- Table produits / rayons -->
        <div class="card">
            <div class="card-head">
                <h2>Rayons & Produits à surveiller</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-download"></i> Exporter</button>
                    <button class="btn"><i class="bi bi-plus-circle"></i> Ajouter un rayon</button>
                </div>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Libellé</th>
                        <th>Rayon</th>
                        <th>Stock</th>
                        <th>DLC / DLUO</th>
                        <th>Statut</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="pill blue"><i class="bi bi-stars"></i> Produit</span></td>
                        <td>Yaourt nature 4x125g</td>
                        <td>Produits frais</td>
                        <td>28</td>
                        <td>—</td>
                        <td><span class="pill success">OK</span></td>
                        <td><a class="link" href="#"><i class="bi bi-pencil"></i> Éditer</a></td>
                    </tr>
                    <tr>
                        <td><span class="pill green"><i class="bi bi-grid-1x2"></i> Rayon</span></td>
                        <td>Boissons</td>
                        <td>—</td>
                        <td>—</td>
                        <td>—</td>
                        <td><span class="pill warning">Images manquantes</span></td>
                        <td><a class="link" href="#"><i class="bi bi-upload"></i> Ajouter visuels</a></td>
                    </tr>
                    <tr>
                        <td><span class="pill blue"><i class="bi bi-stars"></i> Produit</span></td>
                        <td>Pâtes Penne 500g</td>
                        <td>Produits secs</td>
                        <td>0</td>
                        <td>—</td>
                        <td><span class="pill danger">Rupture</span></td>
                        <td><a class="link" href="#"><i class="bi bi-truck"></i> Commander</a></td>
                    </tr>
                    <tr>
                        <td><span class="pill blue"><i class="bi bi-stars"></i> Produit</span></td>
                        <td>Filet de saumon 250g</td>
                        <td>Produits frais</td>
                        <td>12</td>
                        <td>—</td>
                        <td><span class="pill warning">Promo active</span></td>
                        <td><a class="link" href="#"><i class="bi bi-megaphone"></i> Voir promo</a></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-foot">
                <span>4 / 48</span>
                <div class="pager">
                    <button class="btn ghost">‹</button>
                    <button class="btn ghost">›</button>
                </div>
            </div>
        </div>

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
                <label class="grid two">
                    <span>Taux de remise (%)</span>
                    <input type="number" min="1" max="90" placeholder="20" />
                </label>
                <label class="grid two">
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
    $dsn = 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8';
    $bdd = new PDO($dsn, 'root', '');
    $sqlMagasin = $bdd->prepare("SELECT * FROM magasins ");
    $sqlMagasin->execute();
    $lignesMagasins = $sqlMagasin->fetchAll();
    ?>
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
            <div class="table-wrap">
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
                </table>
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
