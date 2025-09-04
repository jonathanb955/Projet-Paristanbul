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
        <h1>Promotions — Administration</h1>
        <div class="top-actions">
            <button class="btn ghost"><i class="bi bi-upload"></i> Import CSV</button>
            <button class="btn"><i class="bi bi-plus-circle"></i> Créer une promo</button>
        </div>
    </header>

    <!-- Filtres -->
    <section class="filters">
        <form class="filters-bar" action="#" method="get">
            <div class="field">
                <i class="bi bi-search"></i>
                <input type="search" placeholder="Rechercher (libellé, rayon, type)…">
            </div>
            <div class="field select">
                <i class="bi bi-grid-1x2"></i>
                <select>
                    <option value="">Rayon (tous)</option>
                    <option>Fruits & Légumes</option>
                    <option>Produits frais</option>
                    <option>Produits secs</option>
                    <option>Boissons</option>
                    <option>Hygiène</option>
                    <option>Surgelés</option>
                    <option>Emballages</option>
                </select>
            </div>
            <div class="field select">
                <i class="bi bi-tags"></i>
                <select>
                    <option value="">Type (tous)</option>
                    <option>-%</option>
                    <option>2+1</option>
                    <option>-50% sur 2e</option>
                    <option>Prix choc</option>
                </select>
            </div>
            <div class="field select">
                <i class="bi bi-lightbulb"></i>
                <select>
                    <option value="">Statut</option>
                    <option>Publiée</option>
                    <option>Brouillon</option>
                    <option>Planifiée</option>
                    <option>Expirée</option>
                </select>
            </div>
            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
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
                    <tr>
                        <td><strong>-30% Fruits de saison</strong></td>
                        <td>Fruits & Légumes</td>
                        <td><span class="pill blue"><i class="bi bi-percent"></i> -%</span></td>
                        <td>30%</td>
                        <td>03/09/2025 → 10/09/2025</td>
                        <td><span class="pill success"><i class="bi bi-check-circle"></i> Publiée</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-files"></i> Dupliquer</button>
                            <button class="btn xs"><i class="bi bi-toggle-off"></i> Dépublier</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>2+1 Yaourts bio</strong></td>
                        <td>Produits frais</td>
                        <td><span class="pill green"><i class="bi bi-plus-circle"></i> 2+1</span></td>
                        <td>Gratuit sur 3e</td>
                        <td>07/09/2025 → 14/09/2025</td>
                        <td><span class="pill warning"><i class="bi bi-clock"></i> Planifiée</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-files"></i> Dupliquer</button>
                            <button class="btn xs"><i class="bi bi-check2-circle"></i> Publier</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>-50% sur 2e saumon</strong></td>
                        <td>Produits frais</td>
                        <td><span class="pill"><i class="bi bi-123"></i> -50% 2e</span></td>
                        <td>-50% 2e</td>
                        <td>01/09/2025 → 05/09/2025</td>
                        <td><span class="pill danger"><i class="bi bi-slash-circle"></i> Expirée</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs"><i class="bi bi-arrow-repeat"></i> Reprogrammer</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-foot">
                <span>3 / 3</span>
                <div class="pager"><button class="btn ghost">‹</button><button class="btn ghost">›</button></div>
            </div>
        </div>

        <!-- Panneau création/édition -->
        <aside class="sidepanel card">
            <div class="card-head"><h2>Créer / Éditer une promo</h2></div>
            <form class="form">
                <label><span>Libellé</span><input type="text" placeholder="Ex : -20% sur les boissons gazeuses"></label>
                <label><span>Rayon</span>
                    <select>
                        <option>Fruits & Légumes</option><option>Produits frais</option><option>Produits secs</option>
                        <option>Boissons</option><option>Hygiène</option><option>Surgelés</option><option>Emballages</option>
                    </select>
                </label>
                <div class="grid two">
                    <label><span>Type</span>
                        <select>
                            <option>-%</option><option>2+1</option><option>-50% sur 2e</option><option>Prix choc</option>
                        </select>
                    </label>
                    <label><span>Valeur</span><input type="text" placeholder="Ex : 20% / 2+1 / 1,99€"></label>
                </div>
                <label><span>Période</span><input type="text" placeholder="JJ/MM/AAAA → JJ/MM/AAAA"></label>
                <label><span>Description (facultatif)</span><textarea rows="4" placeholder="Détails affichés sur la page promotions…"></textarea></label>

                <div class="grid two">
                    <label><span>Statut</span><select><option>Publiée</option><option>Brouillon</option><option>Planifiée</option></select></label>
                    <label><span>Affichage public</span><select><option>Afficher</option><option>Masquer</option></select></label>
                </div>

                <div class="promo-art">
                    <div class="art-thumb"><i class="bi bi-image"></i></div>
                    <div class="art-meta">
                        <strong>Visuel promotion</strong>
                        <p>Image d’entête (optionnelle).</p>
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

    <footer class="footer"><small>© 2025 — Back-office Paristanbul • Promotions</small></footer>
</main>
</body>
</html>
<?php
