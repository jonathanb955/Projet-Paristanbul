<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Utilisateurs</title>
    <link rel="stylesheet" href=../../assets/css/admin.css>
    <link rel="stylesheet" href="../../assets/css/gestionUserAdmin.css">
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
        <a class="menu-item" href="../../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>

        <a class="menu-item active" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>

    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <h1>Utilisateurs — Administration</h1>
        <div class="top-actions">
            <button class="btn"><i class="bi bi-person-plus"></i> Nouvel utilisateur</button>
        </div>
    </header>

    <section class="filters">
        <form class="filters-bar" action="#" method="get">
            <div class="field"><i class="bi bi-search"></i><input type="search" placeholder="Rechercher (nom, email)…"></div>
            <div class="field select"><i class="bi bi-person-badge"></i>
                <select><option value="">Rôle</option><option>Super Admin</option><option>Admin</option><option>Manager</option><option>Éditeur</option></select>
            </div>
            <div class="field select"><i class="bi bi-lightbulb"></i>
                <select><option value="">Statut</option><option>Actif</option><option>Inactif</option><option>Invité</option></select>
            </div>
            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>

    <section class="layout">
        <div class="card">
            <div class="card-head"><h2>Liste des utilisateurs</h2></div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Magasin</th><th>Dernière connexion</th><th>Statut</th><th style="width:240px"></th></tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>Naël Lakhledj</strong></td><td>admin@paristanbul.fr</td><td>Super Admin</td><td>—</td><td>03/09/2025 09:42</td>
                        <td><span class="pill success"><i class="bi bi-check-circle"></i> Actif</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-shield-lock"></i> Réinitialiser MDP</button>
                            <button class="btn xs"><i class="bi bi-toggle-off"></i> Désactiver</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Sarah B.</strong></td><td>sarah@paristanbul.fr</td><td>Manager</td><td>Villiers-le-Bel</td><td>02/09/2025 18:12</td>
                        <td><span class="pill success"><i class="bi bi-check-circle"></i> Actif</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-pencil"></i> Éditer</button>
                            <button class="btn xs ghost"><i class="bi bi-shield-lock"></i> Réinitialiser MDP</button>
                            <button class="btn xs"><i class="bi bi-toggle-off"></i> Désactiver</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Amine K.</strong></td><td>amine@paristanbul.fr</td><td>Éditeur</td><td>Bondy</td><td>—</td>
                        <td><span class="pill warning"><i class="bi bi-hourglass"></i> Invité</span></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-send-check"></i> Renvoyer invitation</button>
                            <button class="btn xs"><i class="bi bi-x-circle"></i> Révoquer</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panneau création/édition -->
        <aside class="sidepanel card">
            <div class="card-head"><h2>Créer / Éditer un utilisateur</h2></div>
            <form class="form">
                <div class="grid two">
                    <label><span>Nom</span><input type="text" placeholder="Nom Prénom"></label>
                    <label><span>Email</span><input type="email" placeholder="email@paristanbul.fr"></label>
                </div>
                <div class="grid two">
                    <label><span>Rôle</span>
                        <select><option>Éditeur</option><option>Manager</option><option>Admin</option><option>Super Admin</option></select>
                    </label>
                    <label><span>Magasin (optionnel)</span>
                        <select><option>—</option><option>Villiers-le-Bel</option><option>Bondy</option><option>Drancy</option></select>
                    </label>
                </div>
                <div class="grid two">
                    <label><span>Statut</span><select><option>Actif</option><option>Inactif</option><option>Invité</option></select></label>
                    <label><span>Mot de passe (temp.)</span><input type="password" placeholder="Générer ou saisir"></label>
                </div>
                <label class="check"><input type="checkbox"><span>Forcer la réinitialisation du mot de passe à la première connexion</span></label>
                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
                    <button type="button" class="btn ghost"><i class="bi bi-envelope-paper"></i> Envoyer invitation</button>
                    <button type="submit" class="btn"><i class="bi bi-check2-circle"></i> Enregistrer</button>
                </div>
            </form>
        </aside>
    </section>

    <footer class="footer"><small>© 2025 — Back-office Paristanbul • Utilisateurs</small></footer>
</main>
</body>
</html>
