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
        <a class="menu-item active" href="../../vue/Admin/nosMagasinsAdmin.php"><i class="bi bi-shop"></i><span>Nos magasins</span></a>
        <div class="menu-title">Contenus</div>

        <a class="menu-item" href="../../vue/Admin/promoAdmin.php"><i class="bi bi-megaphone"></i><span>Promotions</span></a>
        <div class="menu-title">Administration</div>
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
            <div class="field select">
                <i class="bi bi-lightbulb"></i>
                <select>
                    <option value="">Statut (tous)</option>
                    <option>Publié</option>
                    <option>Brouillon</option>
                    <option>Fermé temporairement</option>
                </select>
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
            </div>

            <div class="table-foot">
                <span>3 / 3</span>
                <div class="pager">
                    <button class="btn ghost">‹</button>
                    <button class="btn ghost">›</button>
                </div>
            </div>
        </div>

        <!-- Panneau latéral : Ajouter / Éditer -->
        <aside class="sidepanel card">
            <div class="card-head">
                <h2>Ajouter / Éditer un magasin</h2>
            </div>

            <form class="form">
                <label>
                    <span>Nom du magasin</span>
                    <input type="text" placeholder="Paristanbul — Villiers-le-Bel">
                </label>

                <label>
                    <span>Adresse</span>
                    <input type="text" placeholder="117 Avenue Pierre Sémard">
                </label>

                <div class="grid two">
                    <label>
                        <span>Code postal</span>
                        <input type="text" placeholder="95400">
                    </label>
                    <label>
                        <span>Ville</span>
                        <input type="text" placeholder="Villiers-le-Bel">
                    </label>
                </div>

                <label>
                    <span>Téléphone</span>
                    <input type="tel" placeholder="07 49 82 61 33">
                </label>

                <label>
                    <span>URL itinéraire Google</span>
                    <input type="url" placeholder="https://www.google.com/maps/dir/?api=1&destination=...">
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

                <label>
                    <span>Services (tags)</span>
                    <input type="text" placeholder="Fruits & Légumes, Produits frais, Surgelés, Boissons">
                </label>

                <div class="grid two">
                    <label>
                        <span>Statut</span>
                        <select>
                            <option>Publié</option>
                            <option>Brouillon</option>
                            <option>Fermé temporairement</option>
                        </select>
                    </label>
                    <label>
                        <span>Affichage public</span>
                        <select>
                            <option>Afficher</option>
                            <option>Masquer</option>
                        </select>
                    </label>
                </div>

                <div class="map-preview">
                    <div class="map-thumb">
                        <i class="bi bi-map"></i>
                    </div>
                    <div class="map-meta">
                        <strong>Aperçu carte</strong>
                        <p>Colle ici ton lien Google Maps d’itinéraire ou d’adresse.</p>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
                    <button type="button" class="btn ghost"><i class="bi bi-box-arrow-down"></i> Enregistrer brouillon</button>
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
