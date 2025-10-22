<?php
require_once '../bdd/Bdd.php';
use bdd\Bdd;

$bdd = new Bdd();
$pdo = $bdd->getBdd();

// Vérifier l'ID utilisateur
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID utilisateur manquant ou invalide.");
}

$id = (int) $_GET['id'];

// Récupération de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Utilisateur introuvable.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $mdp = trim($_POST['mdp'] ?? '');

    $errors = [];

    if ($nom === '') $errors[] = "Le nom est requis.";
    if ($prenom === '') $errors[] = "Le prénom est requis.";
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Un email valide est requis.";
    if ($role === '') $errors[] = "Le rôle est requis.";

    // Vérifier si l'email existe déjà
    $check = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND id_utilisateur != ?");
    $check->execute([$email, $id]);
    if ($check->fetchColumn() > 0) {
        $errors[] = "Cet email est déjà utilisé par un autre utilisateur.";
    }

    if (empty($errors)) {
        if ($mdp !== '') {
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
            $sql = "UPDATE utilisateurs SET nom=?, prenom=?, email=?, mdp=?, role=? WHERE id_utilisateur=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $email, $mdp_hash, $role, $id]);
        } else {
            $sql = "UPDATE utilisateurs SET nom=?, prenom=?, email=?, role=? WHERE id_utilisateur=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $email, $role, $id]);
        }

        header("Location: ../../vue/Admin/gestionUserAdmin.php?edit=success");
        exit;
    } else {
        echo '<div class="alert alert-error">' . implode('<br>', $errors) . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Paristanbul — Admin • Modifier Utilisateur</title>
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
        <h1>Modifier un utilisateur</h1>
        <div class="top-actions">
            <a href="../../vue/Admin/gestionUserAdmin.php" class="btn ghost">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </header>

    <section class="layout">
        <div class="card">
            <div class="card-head">
                <h2>Éditer l'utilisateur</h2>
            </div>

            <form class="form" method="post">
                <div class="grid two">
                    <label>
                        <span>Nom</span>
                        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </label>
                    <label>
                        <span>Prénom</span>
                        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </label>
                </div>

                <div class="grid two">
                    <label>
                        <span>Email</span>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </label>
                    <label>
                        <span>Mot de passe (laisser vide pour ne pas changer)</span>
                        <input type="password" name="mdp" placeholder="••••••••">
                    </label>
                </div>

                <label>
                    <span>Rôle</span>
                    <select name="role" required>
                        <option value="Éditeur" <?= $user['role'] === 'Éditeur' ? 'selected' : '' ?>>Éditeur</option>
                        <option value="Manager" <?= $user['role'] === 'Manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="Admin" <?= $user['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="Super Admin" <?= $user['role'] === 'Super Admin' ? 'selected' : '' ?>>Super Admin</option>
                    </select>
                </label>

                <div class="form-actions">
                    <button type="submit" class="btn primary">
                        <i class="bi bi-check-lg"></i> Enregistrer
                    </button>
                    <a href="../../vue/Admin/gestionUserAdmin.php" class="btn ghost">
                        <i class="bi bi-x-lg"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </section>
</main>
</body>
</html>
