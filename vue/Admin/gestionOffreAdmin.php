<?php
session_start();
if (!empty($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
    unset($_SESSION['success_message']);
}
if (!empty($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
    unset($_SESSION['error_message']);
}


// =====================================
// Connexion PDO
// =====================================
try {
    $tries = [
            ['h' => '127.0.0.1', 'p' => 8889, 'u' => 'root', 'pw' => 'root'],  // MAMP
            ['h' => '127.0.0.1', 'p' => 3306, 'u' => 'root', 'pw' => ''],      // WAMP
            ['h' => '127.0.0.1', 'p' => 3306, 'u' => 'root', 'pw' => 'root'],  // XAMPP ou autres configs
    ];

    foreach ($tries as $t) {
        try {
            $pdo = new PDO(
                    "mysql:host={$t['h']};port={$t['p']};dbname=bdd_paristanbul;charset=utf8mb4",
                    $t['u'],
                    $t['pw'],
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            break; // réussite -> on sort de la boucle
        } catch (Throwable $e) {
            $pdo = null;
        }
    }

    if (!$pdo) throw new Exception("Impossible de se connecter à MySQL (testé 8889 et 3306).");

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

// =====================================
// FILTRES & PAGINATION
// =====================================
$search = $_GET['search'] ?? '';
$poste = $_GET['tri-par-poste'] ?? '';
$ville = $_GET['tri-par-magasin'] ?? '';

$limit = 3;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// =====================================
// COMPTE TOTAL DES OFFRES
// =====================================
$sqlCount = "SELECT COUNT(*) FROM offres_emplois WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sqlCount .= " AND (
        LOWER(titre_poste) LIKE LOWER(:search)
        OR LOWER(secteur_activite) LIKE LOWER(:search)
        OR LOWER(detail_poste) LIKE LOWER(:search)
        OR LOWER(ville) LIKE LOWER(:search)
    )";
    $params[':search'] = '%' . $search . '%';
}
if (!empty($poste)) {
    $sqlCount .= " AND titre_poste = :poste";
    $params[':poste'] = $poste;
}
if (!empty($ville)) {
    $sqlCount .= " AND ville = :ville";
    $params[':ville'] = $ville;
}

$stmt = $pdo->prepare($sqlCount);
$stmt->execute($params);
$total = $stmt->fetchColumn();
$totalPages = max(1, ceil($total / $limit));

// =====================================
// REQUÊTE PRINCIPALE AVEC LIMIT/OFFSET
// =====================================
$sql = "SELECT * FROM offres_emplois WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (
        LOWER(titre_poste) LIKE LOWER(:search)
        OR LOWER(secteur_activite) LIKE LOWER(:search)
        OR LOWER(detail_poste) LIKE LOWER(:search)
        OR LOWER(ville) LIKE LOWER(:search)
    )";
}
if (!empty($poste)) {
    $sql .= " AND titre_poste = :poste";
}
if (!empty($ville)) {
    $sql .= " AND ville = :ville";
}

$sql .= " ORDER BY id_offre asc LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

if (!empty($search)) {
    $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
}
if (!empty($poste)) {
    $stmt->bindValue(':poste', $poste, PDO::PARAM_STR);
}
if (!empty($ville)) {
    $stmt->bindValue(':ville', $ville, PDO::PARAM_STR);
}

$stmt->execute();
$lignesOffres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// =====================================
// VILLES DISTINCTES POUR LE FILTRE
// =====================================
$stmtVilles = $pdo->query("SELECT  ville_magasin FROM magasins WHERE ville_magasin IS NOT NULL AND ville_magasin <> '' ORDER BY ville_magasin ASC");
$villes = $stmtVilles->fetchAll(PDO::FETCH_COLUMN);

// =====================================
// FORMULAIRE AJOUT OFFRE
// =====================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titre_poste'])) {
    $secteur_activite = trim($_POST['secteur_activite'] ?? '');
    $titre_poste = trim($_POST['titre_poste'] ?? '');
    $ville_poste = trim($_POST['ville'] ?? '');
    $departement = trim($_POST['departement'] ?? '');
    $type_contrat = trim($_POST['type_contrat'] ?? '');
    $detail_poste = trim($_POST['detail_poste'] ?? '');

    if ($secteur_activite && $titre_poste && $ville_poste && $departement && $type_contrat && $detail_poste) {
        $sqlInsert = $pdo->prepare("
            INSERT INTO offres_emplois 
            (secteur_activite, titre_poste, ville, departement, type_contrat, detail_poste)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $sqlInsert->execute([$secteur_activite, $titre_poste, $ville_poste, $departement, $type_contrat, $detail_poste]);
        header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    } else {
        echo "<p style='color:red;'>Veuillez remplir tous les champs du formulaire.</p>";
    }
}

$departements = ['95', '94', '93', '92', '91', '78', '77','75', '60'];

// =====================================
// Conserver les filtres dans pagination
// =====================================
$queryParams = $_GET;
unset($queryParams['page']);
$queryString = http_build_query($queryParams);

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Offres d'emplois</title>
    <link rel="stylesheet" href="../../assets/css/admin.css" />
    <link rel="stylesheet" href="../../assets/css/gestionOffreAdmin.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />


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

        <a class="menu-item" href="candidatureAdmin.php"><i class="bi bi-briefcase"></i><span>Candidatures</span></a>
        <a class="menu-item" href="../../vue/Admin/gestionUserAdmin.php"><i class="bi bi-people"></i><span>Utilisateurs</span></a>
    </nav>
    <div class="sidebar-footer">
        <a class="btn-outline" href="#"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
</aside>

<main class="main">
    <header class="topbar">
        <h1>Offres — Administration</h1>
        <div class="top-actions">
            <form method="post" style="display:inline;">
                <button type="submit" name="archiver_lues" class="btn ghost">
                    <i class="bi bi-archive"></i> Archiver lues
                </button>
            </form>
            <form method="post" action="../../src/traitement/export_candidatures.php" style="display:inline;">
                <button type="submit" name="export_csv" class="btn">
                    <i class="bi bi-download"></i> Export CSV
                </button>
            </form>
        </div>
    </header>

    <!-- ======================= FILTRES ======================= -->
    <section class="filters">
        <form class="filters-bar" method="get">
            <div class="field">
                <i class="bi bi-search"></i>
                <input type="search" name="search" placeholder="Rechercher (poste, ville…)" value="<?= htmlspecialchars($search) ?>">
            </div>

            <div class="field select"><i class="bi bi-briefcase"></i>
                <select name="tri-par-poste">
                    <option value="">Poste (tous)</option>
                    <option value="Caissier(ère)" <?= $poste === "Caissier(ère)" ? 'selected' : '' ?>>Caissier(ère)</option>
                    <option value="Préparateur de commande" <?= $poste === "Préparateur de commande" ? 'selected' : '' ?>>Préparateur de commande</option>
                    <option value="Comptable" <?= $poste === "Comptable" ? 'selected' : '' ?>>Comptable</option>
                    <option value="Manutentionnaire" <?= $poste === "Manutentionnaire" ? 'selected' : '' ?>>Manutentionnaire</option>
                    <option value="Chauffeur" <?= $poste === "Chauffeur" ? 'selected' : '' ?>>Chauffeur</option>

                </select>
            </div>

            <div class="field select"><i class="bi bi-geo-alt"></i>
                <select name="tri-par-magasin">
                    <option value="">Ville (toutes)</option>
                    <?php foreach ($villes as $v) : ?>
                        <option value="<?= htmlspecialchars($v) ?>" <?= $ville === $v ? 'selected' : '' ?>><?= htmlspecialchars($v) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn"><i class="bi bi-funnel"></i> Filtrer</button>
        </form>
    </section>

    <!-- ======================= TABLEAU ======================= -->
    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Liste des offres d'emplois</h2>
            </div>

            <div class="table-wrap">
                <table class="table" id="offresTable">
                    <thead>
                    <tr>
                        <th>Poste</th>
                        <th>Secteur</th>
                        <th>Contrat</th>
                        <th>Ville</th>
                        <th>Departement</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($lignesOffres)): ?>
                        <tr><td colspan="5" class="text-center">Aucune offre trouvée.</td></tr>
                    <?php else: ?>
                        <?php foreach ($lignesOffres as $offre): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($offre['titre_poste']) ?></strong></td>
                                <td><?= htmlspecialchars($offre['secteur_activite']) ?></td>
                                <td><?= htmlspecialchars($offre['type_contrat']) ?></td>
                                <td><?= htmlspecialchars($offre['ville']) ?></td>
                                <td><?= htmlspecialchars($offre['departement']) ?></td>
                                <td class="row-actions">
                                    <a href="../../src/traitement/editOffre.php?id=<?= $offre['id_offre'] ?>"
                                       class="btn btn-sm btn-outline" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="../../src/traitement/deleteOffre.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id_offre" value="<?= htmlspecialchars($offre['id_offre']) ?>">
                                        <input type="hidden" name="delete_offre" value="1">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Supprimer" onclick="return confirm('Supprimer cette offre ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('add-offer-form');

                // Règles de validation
                const rules = {
                    secteur_activite: { min: 2, max: 50 },
                    titre_poste: { min: 2, max: 50 },
                    detail_poste: { min: 10, max: 100 }
                };

                // Fonction de validation individuelle
                function validateField(field) {
                    const value = field.value.trim();
                    const errorSpan = field.parentElement.querySelector('.error');
                    const name = field.name;
                    let message = '';

                    if (field.required && value === '') {
                        message = 'Ce champ est obligatoire.';
                    } else if (rules[name]) {
                        const { min, max } = rules[name];
                        if (value.length < min) message = `Minimum ${min} caractères.`;
                        if (value.length > max) message = `Maximum ${max} caractères.`;
                    }

                    // Gestion du message d’erreur
                    if (message) {
                        errorSpan.textContent = message;
                        field.classList.add('invalid');
                    } else {
                        errorSpan.textContent = '';
                        field.classList.remove('invalid');
                    }

                    return message === '';
                }

                // Gestion des compteurs de caractères
                document.querySelectorAll('[maxlength]').forEach(field => {
                    const limit = field.getAttribute('maxlength');
                    const info = field.parentElement.querySelector('.char-limit');
                    field.addEventListener('input', () => {
                        const remaining = limit - field.value.length;
                        info.textContent = `${remaining} caractères restants`;
                        if (remaining <= 50) {
                            info.style.color = '#e67e22';
                        } else {
                            info.style.color = '';
                        }
                        validateField(field); // validation live
                    });
                });

                // Validation à chaque saisie / changement
                form.querySelectorAll('input, textarea, select').forEach(field => {
                    field.addEventListener('input', () => validateField(field));
                    field.addEventListener('change', () => validateField(field));
                });

                // Validation globale avant soumission
                form.addEventListener('submit', e => {
                    let allValid = true;
                    form.querySelectorAll('input, textarea, select').forEach(field => {
                        if (!validateField(field)) allValid = false;
                    });
                    if (!allValid) {
                        e.preventDefault();
                        alert('Veuillez corriger les erreurs avant de soumettre le formulaire.');
                    }
                });
            });
        </script>
        <style>
            .form-control {
                position: relative;
                margin-bottom: 1rem;
            }

            .form-control .error {
                color: #e74c3c;
                font-size: 0.85rem;
                display: block;
                margin-top: 4px;
            }

            input.invalid, textarea.invalid, select.invalid {
                border: 1px solid #e74c3c !important;
                background-color: #fff6f6;
            }

        </style>
        <!-- ======================= FORMULAIRE AJOUT ======================= -->
        <style>
            .form-control {
                position: relative;
                margin-bottom: 1rem;
            }

            .form-control .error {
                color: #e74c3c;
                font-size: 0.85rem;
                display: block;
                margin-top: 4px;
            }

            input.invalid, textarea.invalid, select.invalid {
                border: 1px solid #e74c3c !important;
                background-color: #fff6f6;
            }

            .char-limit {
                font-size: 0.8rem;
                color: #888;
            }
        </style>

        <!-- ======================= FORMULAIRE AJOUT ======================= -->
        <div class="card">
            <div class="card-head">
                <h2>Ajouter une offre</h2>
            </div>
            <form method="POST" class="form" id="add-offer-form" novalidate>
                <div class="form-control">
                    <label>Secteur d'activité</label>
                    <input type="text" name="secteur_activite" id="secteur_activite" maxlength="30" required>
                    <small class="char-limit">30 caractères max</small>
                    <span class="error"></span>
                </div>

                <div class="form-control">
                    <label>Titre du poste</label>
                    <input type="text" name="titre_poste" id="titre_poste" maxlength="30" required>
                    <small class="char-limit">30 caractères max</small>
                    <span class="error"></span>
                </div>

                <div class="form-control">
                    <label>Ville</label>
                    <select name="ville" id="ville" required>
                        <option value="">Choisissez une ville</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= htmlspecialchars($v) ?>"><?= htmlspecialchars($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"></span>
                </div>

                <div class="form-control">
                    <label>Département</label>
                    <select name="departement" id="departement" required>
                        <option value="">Choisissez un département</option>
                        <?php foreach ($departements as $dep): ?>
                            <option value="<?= htmlspecialchars($dep) ?>"><?= htmlspecialchars($dep) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error"></span>
                </div>

                <div class="form-control">
                    <label>Type de contrat</label>
                    <select name="type_contrat" id="type_contrat" required>
                        <option value="">Choisissez un type de contrat</option>
                        <option value="CDD">CDD</option>
                        <option value="CDI">CDI</option>
                        <option value="Contrat de professionnalisation">Contrat de professionnalisation</option>
                        <option value="Stage">Stage</option>
                        <option value="Apprentissage">Apprentissage</option>
                    </select>
                    <span class="error"></span>
                </div>

                <div class="form-control">
                    <label>Détail du poste</label>
                    <textarea name="detail_poste" id="detail_poste" rows="4" maxlength="50" required></textarea>
                    <small class="char-limit">50 caractères max</small>
                    <span class="error"></span>
                </div>

                <button type="submit" class="btn">Ajouter l'offre</button>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('add-offer-form');

                // Règles de validation
                const rules = {
                    secteur_activite: { min: 2, max: 30 },
                    titre_poste: { min: 2, max: 30 },
                    detail_poste: { min: 10, max: 50 }
                };

                // Fonction de validation individuelle
                function validateField(field) {
                    const value = field.value.trim();
                    const errorSpan = field.parentElement.querySelector('.error');
                    const name = field.name;
                    let message = '';

                    if (field.required && value === '') {
                        message = 'Ce champ est obligatoire.';
                    } else if (rules[name]) {
                        const { min, max } = rules[name];
                        if (value.length < min) message = `Minimum ${min} caractères.`;
                        else if (value.length > max) message = `Maximum ${max} caractères.`;
                    }

                    // Gestion du message d’erreur
                    if (message) {
                        errorSpan.textContent = message;
                        field.classList.add('invalid');
                    } else {
                        errorSpan.textContent = '';
                        field.classList.remove('invalid');
                    }

                    return message === '';
                }

                // Gestion des compteurs de caractères
                document.querySelectorAll('[maxlength]').forEach(field => {
                    const limit = field.getAttribute('maxlength');
                    const info = field.parentElement.querySelector('.char-limit');
                    field.addEventListener('input', () => {
                        const remaining = limit - field.value.length;
                        info.textContent = `${remaining} caractères restants`;
                        info.style.color = remaining <= 20 ? '#e67e22' : '';
                        validateField(field); // validation live
                    });
                });

                // Validation à chaque saisie / changement
                form.querySelectorAll('input, textarea, select').forEach(field => {
                    field.addEventListener('input', () => validateField(field));
                    field.addEventListener('change', () => validateField(field));
                });

                // Validation globale avant soumission
                form.addEventListener('submit', e => {
                    let allValid = true;
                    form.querySelectorAll('input, textarea, select').forEach(field => {
                        if (!validateField(field)) allValid = false;
                    });
                    if (!allValid) {
                        e.preventDefault();
                        alert('Veuillez corriger les erreurs avant de soumettre le formulaire.');
                    }
                });
            });
        </script>

        <!-- Design form -->
        <style>
            textarea, input, select {
                color: #222 !important;          /* texte noir lisible */
                background-color: #fff !important; /* fond blanc */
                border: 1px solid #ccc !important;
            }

            textarea::placeholder,
            input::placeholder {
                color: #888;
            }


            textarea:focus, input:focus, select:focus {
                outline: none;
                border-color: #e74c3c;
                box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2);
            }

        </style>

    </section>
</main>
</body>
</html>
