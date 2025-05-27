<?php
session_start();
$connecte = isset($_SESSION['connexion']) && $_SESSION['connexion'] === true;

if (!$connecte) {
    header("Location: ../../vue/pageConnexion.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=projet_vol;charset=utf8', 'root', '');
$id_utilisateur = $_SESSION['id_utilisateur'];


$req = $pdo->prepare("SELECT id_utilisateur, nom, prenom, date_naissance, ville_residence, email FROM utilisateur WHERE id_utilisateur = ?");
$req->execute([$id_utilisateur]);
$client = $req->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    echo "❌ Aucun utilisateur trouvé pour l'ID : $id_utilisateur";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'])) {
    $update = $pdo->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, date_naissance = ?, ville_residence = ?, email = ? WHERE id_utilisateur = ?");
    $update->execute([
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['date_naissance'],
        $_POST['ville_residence'],
        $_POST['email'],
        $id_utilisateur
    ]);
    header("Location: espaceClient.php?updated=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Client</title>
    <link href="../assets/css/espaceClient.css" rel="stylesheet">
</head>
<body>
<h1>👤 Mon Espace Client</h1>

<?php if (isset($_GET['updated'])): ?>
    <p>✅ Vos informations ont été mises à jour.</p>
<?php endif; ?>


<div class="card">
    <form method="post">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>

        <label for="date_naissance">Date de Naissance</label>
        <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($client['date_naissance']) ?>" required>

        <label for="ville_residence">Ville de Résidence</label>
        <input type="text" id="ville_residence" name="ville_residence" value="<?= htmlspecialchars($client['ville_residence']) ?>" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

        <button type="submit">💾 Mettre à jour mes infos</button>
    </form>
</div>


<div class="card">
    <h2>🛫 Mes Réservations</h2>
    <?php
    $reservations = $pdo->prepare("
        SELECT r.id_reservation, r.date_reservation, r.prix_billet, 
               v.destination, v.date_depart, v.heure_depart
        FROM reservation AS r
        JOIN vols  AS v ON r.ref_vol = v.id_vol
        WHERE r.ref_utilisateur = ?
        ORDER BY r.date_reservation DESC
    ");
    $reservations->execute([$id_utilisateur]);
    $res = $reservations->fetchAll(PDO::FETCH_ASSOC);


    if ($res) {
        foreach ($res as $reservation) {
            echo "<div class='reservation-card'>";
            echo "<p><strong>✈️ Vol vers :</strong> " . htmlspecialchars($reservation['destination']) . "</p>";
            echo "<p><strong>🗓 Départ :</strong> " . htmlspecialchars($reservation['date_depart']) . " à " . htmlspecialchars($reservation['heure_depart']) . "</p>";
            echo "<p><strong>📅 Réservé le :</strong> " . htmlspecialchars($reservation['date_reservation']) . "</p>";
            echo "<p><strong>💶 Prix :</strong> " . htmlspecialchars($reservation['prix_billet']) . " €</p>";
            echo '<div style="display: flex; justify-content: center; align-items: center;">';
            echo '<a href="suppReservation.php?id_reservation=' . htmlspecialchars($reservation['id_reservation']) . '&id_utilisateur=' . htmlspecialchars($_SESSION['id_utilisateur']) . '" 
                  class="btn btn-danger btn-sm" 
                  onclick="return confirm(\'Voulez-vous vraiment supprimer cette réservation ?\');">
                  <button type="button" style="background-color: red; color: white" class="btn btn-danger btn-sm"> 🗑 Annuler</button>
              </a>';
            echo '</div>';

            echo "</div><hr>";
        }
    } else {
        echo "<p>Vous n'avez encore effectué aucune réservation.</p>";
    }



    ?>
</div>

<form action="index.php" method="get">
    <button type="submit" class="btn btn-light partenaire" style="background-color: #0d6efd; color: white; text-align: center">Retour</button>
</form>
<br>
<br>
</body>
</html>

