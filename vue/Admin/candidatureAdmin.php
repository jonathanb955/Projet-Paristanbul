<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Candidatures</title>
    <link rel="stylesheet" href=../../assets/css/admin.css />
    <link rel="stylesheet" href=../../assets/css/candidatureAdmin.css />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<aside class="sidebar">
    <div class="brand"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul"></div>
    <nav class="menu">
        <div class="menu-title">Navigation site</div>
        <a class="menu-item" href="admin-dashboard.html"><i class="bi bi-speedometer2"></i><span>Tableau de bord</span></a>
        <a class="menu-item" href="admin-nos-magasins.html"><i class="bi bi-shop"></i><span>Nos magasins</span></a>
        <a class="menu-item" href="admin-promos.html"><i class="bi bi-megaphone"></i><span>Promotions</span></a>
        <a class="menu-item active" href="#"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>
        <div class="menu-title">Administration</div>
        <a class="menu-item" href="admin-users.html"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
        <a class="menu-item" href="admin-settings.html"><i class="bi bi-gear"></i><span>Paramètres</span></a>
    </nav>
    <div class="sidebar-footer">
        <div class="mini-user"><div class="avatar">PI</div><div class="meta"><strong>Admin Paristanbul</strong><small>Connecté</small></div></div>
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <h1>Candidatures — Administration</h1>
        <div class="top-actions">
            <button class="btn ghost"><i class="bi bi-archive"></i> Archiver lues</button>
            <button class="btn"><i class="bi bi-download"></i> Export CSV</button>
        </div>
    </header>

    <section class="filters">
        <form class="filters-bar" action="#" method="get">
            <div class="field"><i class="bi bi-search"></i><input type="search" placeholder="Rechercher (nom, email, poste)…"></div>
            <div class="field select"><i class="bi bi-briefcase"></i>
                <select><option value="">Poste (tous)</option><option>Caissier(ère)</option><option>Préparateur(trice)</option><option>Manager</option></select>
            </div>
            <div class="field select"><i class="bi bi-geo-alt"></i>
                <select><option value="">Magasin (tous)</option><option>Villiers-le-Bel</option><option>Bondy</option><option>Drancy</option></select>
            </div>
            <div class="field select"><i class="bi bi-lightbulb"></i>
                <select><option value="">Statut</option><option>Nouveau</option><option>Retenu</option><option>Refusé</option><option>Archivé</option></select>
            </div>
            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>

    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Liste des candidatures</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-eye"></i> Aperçu formulaire public</button>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom</th><th>Email</th><th>Tél.</th><th>Poste</th><th>Magasin</th><th>Statut</th><th>CV</th><th style="width:240px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><strong>Sarah Benali</strong></td><td>sarah.b@example.com</td><td>06 12 34 56 78</td>
                        <td>Caissière</td><td>Villiers-le-Bel</td>
                        <td><span class="pill warning"><i class="bi bi-star"></i> Nouveau</span></td>
                        <td><a class="link" href="#"><i class="bi bi-file-earmark-text"></i> CV.pdf</a></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-eye"></i> Voir</button>
                            <button class="btn xs"><i class="bi bi-check2-circle"></i> Retenir</button>
                            <button class="btn xs ghost"><i class="bi bi-x-circle"></i> Refuser</button>
                            <button class="btn xs ghost"><i class="bi bi-archive"></i> Archiver</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Kamel Idrissi</strong></td><td>k.idrissi@example.com</td><td>07 88 77 66 55</td>
                        <td>Préparateur</td><td>Bondy</td>
                        <td><span class="pill success"><i class="bi bi-check-circle"></i> Retenu</span></td>
                        <td><a class="link" href="#"><i class="bi bi-file-earmark-text"></i> CV.docx</a></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-eye"></i> Voir</button>
                            <button class="btn xs ghost"><i class="bi bi-arrow-counterclockwise"></i> Revenir en “Nouveau”</button>
                            <button class="btn xs ghost"><i class="bi bi-archive"></i> Archiver</button>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Léa P.</strong></td><td>lea.p@example.com</td><td>06 55 44 33 22</td>
                        <td>Manager</td><td>Drancy</td>
                        <td><span class="pill danger"><i class="bi bi-x-circle"></i> Refusé</span></td>
                        <td><a class="link" href="#"><i class="bi bi-file-earmark-text"></i> CV.pdf</a></td>
                        <td class="row-actions">
                            <button class="btn xs ghost"><i class="bi bi-eye"></i> Voir</button>
                            <button class="btn xs"><i class="bi bi-check2-circle"></i> Retenir</button>
                            <button class="btn xs ghost"><i class="bi bi-archive"></i> Archiver</button>
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

        <!-- Panneau détail -->
        <aside class="sidepanel card">
            <div class="card-head"><h2>Détail candidature</h2></div>
            <form class="form">
                <div class="grid two">
                    <label><span>Nom</span><input type="text" placeholder="Nom Prénom"></label>
                    <label><span>Email</span><input type="email" placeholder="email@exemple.com"></label>
                </div>
                <div class="grid two">
                    <label><span>Téléphone</span><input type="tel" placeholder="06 .. .. .. .."></label>
                    <label><span>Poste</span>
                        <select><option>Caissier(ère)</option><option>Préparateur(trice)</option><option>Manager</option></select>
                    </label>
                </div>
                <label><span>Magasin souhaité</span>
                    <select><option>Villiers-le-Bel</option><option>Bondy</option><option>Drancy</option></select>
                </label>
                <label><span>Message</span><textarea rows="4" placeholder="Lettre de motivation…"></textarea></label>
                <div class="grid two">
                    <label><span>Statut</span>
                        <select><option>Nouveau</option><option>Retenu</option><option>Refusé</option><option>Archivé</option></select>
                    </label>
                    <label><span>Note interne</span><input type="text" placeholder="Ex : bon contact tél."></label>
                </div>
                <div class="cv-box">
                    <div class="cv-thumb"><i class="bi bi-file-earmark-text"></i></div>
                    <div class="cv-meta"><strong>CV du candidat</strong><p><a class="link" href="#">Télécharger</a></p></div>
                </div>
                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
                    <button type="button" class="btn ghost"><i class="bi bi-save"></i> Enregistrer</button>
                    <button type="submit" class="btn"><i class="bi bi-send"></i> Contacter</button>
                </div>
            </form>
        </aside>
    </section>

    <footer class="footer"><small>© 2025 — Back-office Paristanbul • Candidatures</small></footer>
</main>
</body>
</html>
