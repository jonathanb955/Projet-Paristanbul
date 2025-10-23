<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Utilisateurs</title>
    <link rel="stylesheet" href="../../assets/css/admin.css">
    <link rel="stylesheet" href="../../assets/css/gestionUserAdmin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        table.table tbody td.row-actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

    </style>


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


        <div class="menu-title">Administration</div>
        <a class="menu-item" href="gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
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
            <div class="field"><i class="bi bi-search"></i><input type="search" name="search" placeholder="Rechercher (nom, email)…" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"></div>
            <div class="field select"><i class="bi bi-person-badge"></i>
                <select name="role">
                    <option value="">Rôle</option>
                    <option <?= (($_GET['role'] ?? '') === 'Super Admin') ? 'selected' : '' ?>>Super Admin</option>
                    <option <?= (($_GET['role'] ?? '') === 'Admin') ? 'selected' : '' ?>>Admin</option>
                    <option <?= (($_GET['role'] ?? '') === 'Manager') ? 'selected' : '' ?>>Manager</option>
                    <option <?= (($_GET['role'] ?? '') === 'Éditeur') ? 'selected' : '' ?>>Éditeur</option>
                </select>
            </div>
            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>



    <?php
    require_once '../../src/bdd/Bdd.php';
    $bdd = new \bdd\Bdd();
    $pdo = $bdd->getBdd();

    // Traitement POST - Création utilisateur
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Nettoyage des données POST
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mdp = $_POST['mdp'] ?? '';
        $role = $_POST['role'] ?? '';

        // Validation basique
        $errors = [];
        if ($nom === '') $errors[] = "Le nom est requis.";
        if ($prenom === '') $errors[] = "Le prénom est requis.";
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Un email valide est requis.";
        if ($mdp === '') $errors[] = "Le mot de passe est requis.";
        if ($role === '') $errors[] = "Le rôle est requis.";

        // Vérifier si email existe déjà
        if (empty($errors)) {
            $sqlCheck = "SELECT COUNT(*) FROM utilisateurs WHERE email = ?";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->execute([$email]);
            if ($stmtCheck->fetchColumn() > 0) {
                $errors[] = "Cet email est déjà utilisé.";
            }
        }

        if (empty($errors)) {
            // Hash du mot de passe
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

            // Insertion en base
            $sql = "INSERT INTO utilisateurs (nom, prenom, email, mdp, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $email, $mdp_hash, $role]);

            // Redirection pour éviter double POST et actualiser la liste
            header("Location: gestionUserAdmin.php");
            exit;
        } else {
            // Affichage des erreurs
            echo '<div style="padding:10px; background-color:#f8d7da; color:#721c24; margin:10px 0; border-radius:4px;">' . implode('<br>', $errors) . '</div>';
        }
    }

    // Construction dynamique de la requête pour affichage utilisateurs avec filtres et pagination
    $conditions = [];
    $params = [];

    if (!empty($_GET['search'])) {
        $search = '%' . $_GET['search'] . '%';
        $conditions[] = "(nom LIKE :search OR prenom LIKE :search OR email LIKE :search)";
        $params[':search'] = $search;
    }
    if (!empty($_GET['role'])) {
        $conditions[] = "role = :role";
        $params[':role'] = $_GET['role'];
    }

    $limit  = 3;
    $page   = max(1, intval($_GET['page'] ?? 1));
    $offset = ($page - 1) * $limit;

    // Compte total avec filtres
    $sqlCount = "SELECT COUNT(*) FROM utilisateurs";
    if ($conditions) {
        $sqlCount .= " WHERE " . implode(" AND ", $conditions);
    }
    $stmtCount = $pdo->prepare($sqlCount);
    $stmtCount->execute($params);
    $total = $stmtCount->fetchColumn();
    $totalPages = ceil($total / $limit);

    // Récupération utilisateurs paginée avec filtres
    $sql = "SELECT * FROM utilisateurs";
    if ($conditions) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    $sql .= " ORDER BY id_utilisateur ASC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Préparer la query string pour la pagination sans le paramètre page
    $queryParams = $_GET;
    unset($queryParams['page']);
    $queryString = http_build_query($queryParams);
    ?>

    <section class="layout">
        <div class="card">
            <div class="card-head"><h2>Liste des utilisateurs</h2></div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                    <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Rôle</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($utilisateurs as $utilisateur): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($utilisateur['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($utilisateur['prenom']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['email']) ?></td>
                            <td><?= htmlspecialchars($utilisateur['role']) ?></td>
                            <td class="row-actions">
                                <a href="../../src/traitement/editUser.php?id=<?= $utilisateur['id_utilisateur'] ?>"
                                   class="btn btn-sm btn-outline" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="../../src/traitement/deleteUser.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $utilisateur['id_utilisateur'] ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                            onclick="return confirm('Supprimer cet utilisateur ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="table-foot">
                    <span><?= $page ?> / <?= $totalPages ?></span>
                    <div class="pager">
                        <?php if ($page > 1): ?>
                            <a class="btn ghost" href="?page=<?= $page - 1 ?><?= $queryString ? '&' . $queryString : '' ?>">‹</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a class="btn ghost" href="?page=<?= $page + 1 ?><?= $queryString ? '&' . $queryString : '' ?>">›</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>


        <!-- Panneau création/édition -->
        <aside class="sidepanel card">
            <div class="card-head"><h2>Créer / Éditer un utilisateur</h2></div>
            <form class="form" method="post" action="gestionUserAdmin.php">
                <div class="grid two">
                    <label><span>Nom</span><input type="text" name="nom" placeholder="Nom" required></label>
                    <label><span>Prénom</span><input type="text" name="prenom" placeholder="Prénom" required></label>
                </div>
                <div class="grid two">
                    <label><span>Email</span><input type="email" name="email" placeholder="email@paristanbul.fr" required></label>
                </div>
                <div class="grid two">
                    <label><span>Mot de passe</span><input type="password" name="mdp" placeholder="Mot de passe" required></label>
                </div>
                <div class="grid two">
                    <label><span>Rôle</span>
                        <select name="role" required>
                            <option value="">-- Choisir un rôle --</option>
                            <option>Éditeur</option>
                            <option>Manager</option>
                            <option>Admin</option>
                            <option>Super Admin</option>
                        </select>
                    </label>
                </div>
                <button class="btn primary" type="submit">Créer</button>
            </form>
        </aside>
    </section>
</main>
</body>
</html>
