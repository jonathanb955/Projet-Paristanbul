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

            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>

    <?php
    require_once '../../src/bdd/Bdd.php';
    $bdd = new \bdd\Bdd();
    $pdo = $bdd->getBdd();
    $query = "SELECT * FROM utilisateurs";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <section class="layout">
        <div class="card">
            <div class="card-head"><h2>Liste des utilisateurs</h2></div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th style="width:240px"></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($utilisateur['nom']) ?></strong></td>
                        <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                        <td><?= htmlspecialchars($utilisateur['role']) ?></td><td>
                    </tr>
                    <?php endforeach; ?>
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
