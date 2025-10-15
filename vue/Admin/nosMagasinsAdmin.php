<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Nos magasins</title>
    <link rel="stylesheet" href="../../assets/css/nosMagasinAdmin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            line-height: 1.5;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 6px 12px;
            background-color: #eee;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination a.active {
            background-color: #333;
            color: #fff;
            font-weight: bold;
        }
        .pagination a.prev,
        .pagination a.next {
            font-weight: bold;
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


        <div class="menu-title">Administration</div>
        <a class="menu-item" href="gestionOffreAdmin.php"><i class="bi bi-briefcase"></i><span>Offres</span></a>
        <a class="menu-item" href="../../vue/Admin/candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>
        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
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
                <input type="search" name="search" placeholder="Rechercher (nom, ville, code postal) …"
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="field">
                <i class="bi bi-geo-alt"></i>
                <input type="text" name="ville" placeholder="Ville…"
                       value="<?= htmlspecialchars($_GET['ville'] ?? '') ?>">
            </div>
            <div class="field">
                <i class="bi bi-123"></i>
                <input type="text" name="cp" placeholder="Code postal…"
                       value="<?= htmlspecialchars($_GET['cp'] ?? '') ?>">
            </div>

            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
        <small class="filters-note">Astuce : exporte ta liste en CSV pour la réimporter après modification.</small>
    </section>

    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Magasins</h2>
                <div class="actions">
                    <button class="btn ghost"><i class="bi bi-download"></i> Exporter</button>
                    <button class="btn ghost"><i class="bi bi-eye"></i> Aperçu public</button>
                </div>
            </div>

            <?php
            require_once "../../src/bdd/Bdd.php";
            $bddObj = new \bdd\Bdd();
            $pdo = $bddObj->getBdd();

            // Lecture des critères de filtre
            $conditions = [];
            $params = [];

            if (!empty($_GET['search'])) {
                $s = '%' . $_GET['search'] . '%';
                $conditions[] = "(ville_magasin LIKE :search OR rue LIKE :search OR cp LIKE :search)";
                $params[':search'] = $s;
            }
            if (!empty($_GET['ville'])) {
                $conditions[] = "ville_magasin LIKE :ville";
                $params[':ville'] = '%' . $_GET['ville'] . '%';
            }
            if (!empty($_GET['cp'])) {
                $conditions[] = "cp LIKE :cp";
                $params[':cp'] = '%' . $_GET['cp'] . '%';
            }

            // Pagination
            $limit = 3;
            $page = max(1, intval($_GET['page'] ?? 1));
            $offset = ($page - 1) * $limit;

            // Compter total avec filtres
            $sqlCount = "SELECT COUNT(*) FROM magasins";
            if ($conditions) {
                $sqlCount .= " WHERE " . implode(" AND ", $conditions);
            }
            $stmtCount = $pdo->prepare($sqlCount);
            $stmtCount->execute($params);
            $total = $stmtCount->fetchColumn();
            $totalPages = ceil($total / $limit);

            // Récupérer les magasins avec filtres + pagination
            $sql = "SELECT * FROM magasins";
            if ($conditions) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
            $sql .= " ORDER BY id_magasin ASC LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            foreach ($params as $key => $val) {
                $stmt->bindValue($key, $val);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $lignesMagasins = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Préparer URL de pagination sans duplication de page
            $queryParams = $_GET;
            unset($queryParams['page']);

            $prevUrl = "?page=" . ($page - 1);
            if (!empty($queryParams)) {
                $prevUrl .= "&" . http_build_query($queryParams);
            }
            $nextUrl = "?page=" . ($page + 1);
            if (!empty($queryParams)) {
                $nextUrl .= "&" . http_build_query($queryParams);
            }
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
                    <?php foreach ($lignesMagasins as $magasin): ?>
                        <tr>
                            <td><?= htmlspecialchars($magasin['ville_magasin']) ?></td>
                            <td><?= htmlspecialchars($magasin['rue']) ?>, <?= htmlspecialchars($magasin['cp']) ?></td>
                            <td><?= htmlspecialchars($magasin['ville_magasin']) ?></td>
                            <td><?= htmlspecialchars($magasin['horaire_ouverture'] . '–' . $magasin['horaire_fermeture']) ?></td>
                            <td><?= htmlspecialchars($magasin['num_tel']) ?></td>
                            <td><a class="link" href="#"><i class="bi bi-pencil"></i> Éditer</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="table-foot">
                    <span><?= $page ?> / <?= $totalPages ?></span>
                    <div class="pager">
                        <?php if ($page > 1): ?>
                            <a class="btn ghost" href="<?= htmlspecialchars($prevUrl) ?>">‹</a>
                        <?php endif; ?>
                        <?php if ($page < $totalPages): ?>
                            <a class="btn ghost" href="<?= htmlspecialchars($nextUrl) ?>">›</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <?php
        // Gestion du POST (ajout magasin)
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // On pourrait faire une redirection après insertion
            $ville = $_POST['ville'] ?? '';
            $ville = strtoupper($ville);
            $adresse = $_POST['adresse'] ?? '';
            $cp = $_POST['cp'] ?? '';
            $rue_complete = $adresse . " " . $cp;
            $tel = $_POST['num_telephone'] ?? '';
            $horaire_ouverture = $_POST['horaire_ouverture'] ?? '';
            $horaire_fermeture = $_POST['horaire_fermeture'] ?? '';
            $jours_selectionnes = $_POST['jours_ouverture'] ?? [];
            $jours_ouverture = implode(', ', $jours_selectionnes);

            $sqlInsert = "INSERT INTO magasins (
                ville_magasin, rue, image, cp, num_tel, horaire_ouverture, horaire_fermeture, jours_ouverture, video_magasin
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmtIns = $pdo->prepare($sqlInsert);
            $stmtIns->execute([
                    $ville,
                    $rue_complete,
                    "",
                    $cp,
                    $tel,
                    $horaire_ouverture,
                    $horaire_fermeture,
                    $jours_ouverture,
                    ""
            ]);

            // Redirection pour éviter la double soumission
            header("Location: " . basename(__FILE__) . "?" . http_build_query($_GET));
            exit;
        }
        ?>

        <aside class="sidepanel card">
            <div class="card-head">
                <h2>Ajouter / Éditer un magasin</h2>
            </div>
            <form class="form" method="post">
                <label>
                    <span>Adresse</span>
                    <input type="text" name="adresse" placeholder="117 Avenue Pierre Sémard">
                </label>
                <div class="grid two">
                    <label>
                        <span>Code postal</span>
                        <input type="text" name="cp" placeholder="95400">
                    </label>
                    <label>
                        <span>Ville</span>
                        <input type="text" name="ville" placeholder="Villiers‑le‑Bel">
                    </label>
                </div>
                <label>
                    <span>Téléphone</span>
                    <input type="tel" name="num_telephone" placeholder="07 49 82 61 33">
                </label>
                <div class="grid two">
                    <label>
                        <span>Heure d'ouverture</span>
                        <input type="time" name="horaire_ouverture" required>
                    </label>
                    <label>
                        <span>Heure de fermeture</span>
                        <input type="time" name="horaire_fermeture" required>
                    </label>
                </div>
                <fieldset style="border: none; padding: 0; margin: 0;">
                    <legend style="font-weight: bold; margin-bottom: 8px;">Jours d'ouverture</legend>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px;">
                        <label class="checkbox-label"><input type="checkbox" name="jours_ouverture[]" value="Lundi">Lundi</label>
                        <label class="checkbox-label"><input type="checkbox" name="jours_ouverture[]" value="Mardi">Mardi</label>
                        <label class="checkbox-label"><input type="checkbox" name="jours_ouverture[]" value="Mercredi">Mercredi</label>
                        <label class="checkbox-label"><input type="checkbox" name="jours_ouverture[]" value="Jeudi">Jeudi</label>
                        <label class="checkbox-label"><input type="checkbox" name="jours_ouverture[]" value="Vendredi">Vendredi</label>
                        <label class="checkbox-label"><input type="checkbox" name="jours_ouverture[]" value="Samedi">Samedi</label>
                        <label class="checkbox-label"><input type="checkbox" name="jours_ouverture[]" value="Dimanche">Dimanche</label>
                    </div>
                </fieldset>
                <div class="form-actions">
                    <button type="reset" class="btn ghost">Annuler</button>
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
