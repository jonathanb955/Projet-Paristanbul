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
        <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
    </div>

    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="admin-dashboard.html"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="menu-item" href="admin-contenus.html"><i class="bi bi-grid-1x2"></i><span>Contenus</span></a>
        <a class="menu-item active" href="#"><i class="bi bi-shop"></i><span>Nos magasins</span></a>
        <a class="menu-item" href="admin-promos.html"><i class="bi bi-megaphone"></i><span>Promotions</span></a>
        <a class="menu-item" href="admin-messages.html"><i class="bi bi-envelope"></i><span>Messages</span></a>
        <div class="menu-title">Administration</div>
        <a class="menu-item" href="admin-users.html"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
        <a class="menu-item" href="admin-settings.html"><i class="bi bi-gear"></i><span>Paramètres</span></a>
    </nav>

    <div class="sidebar-footer">
        <div class="mini-user">
            <div class="avatar">PI</div>
            <div class="meta"><strong>Admin Paristanbul</strong><small>Connecté</small></div>
        </div>
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
                    <tr>
                        <td><strong>Paristanbul Villiers-le-Bel</strong></td>
                        <td>117 Av. Pierre Sémard, 95400</td>
                        <td>Villiers-le-Bel</td>
                        <td>8h30–20h</td>
                        <td>07 49 82 61 33</td>
                        <td><span class="pill success"><i class="bi bi-check-circle"></i> Publié</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-signpost-2"></i> Itinéraire</button>
                            <button class="btn xs ghost"><i class="bi bi-toggle-off"></i> Dépublier</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Paristanbul Bondy</strong></td>
                        <td>116 Av. Galliéni, 93140</td>
                        <td>Bondy</td>
                        <td>8h30–20h</td>
                        <td>07 49 82 61 33</td>
                        <td><span class="pill warning"><i class="bi bi-hourglass"></i> Brouillon</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-geo"></i> Carte</button>
                            <button class="btn xs"><i class="bi bi-toggle-on"></i> Publier</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Paristanbul Drancy</strong></td>
                        <td>83 Av. Marceau, 93700</td>
                        <td>Drancy</td>
                        <td>8h30–20h30</td>
                        <td>07 49 82 61 33</td>
                        <td><span class="pill danger"><i class="bi bi-slash-circle"></i> Fermé temp.</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-geo-alt"></i> Adresse</button>
                            <button class="btn xs"><i class="bi bi-check2-circle"></i> Réouvrir</button>
                        </td>
                    </tr>
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
