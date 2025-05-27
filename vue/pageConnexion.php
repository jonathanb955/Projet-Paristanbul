<?php
if (isset($_GET['error']) && $_GET['error'] == 'unauthorized') {
    echo '<div class="error-message">Accès réservé aux administrateurs.</div>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Page de Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/pageConnexion.css">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h2>Bienvenue</h2>
        <p>Connectez-vous à votre compte</p>
        <form action="../src/traitement/gestionConnexion.php" method="POST">
            <div class="textbox">
                <input type="text" placeholder="Email" name="emailCo" required>
            </div>
            <div class="textbox">
                <input type="password" placeholder="Mot de passe" name="mdpCo" required>
            </div>

            <?php
            if (isset($_GET['parametre'])) {
                if ($_GET['parametre'] == 'emailmdpInvalide') {
                    echo "<p style='color: red;'>❌ Email ou mot de passe invalide.</p>";
                } elseif ($_GET['parametre'] == 'infoManquante') {
                    echo "<p style='color: red;'>❌ Merci de remplir tous les champs.</p>";
                }
            }
            ?>

            <button type="submit" class="btn">Se connecter</button>
        </form>
        <p class="footer">Vous n'avez pas de compte ? <a href="pageInscription.php">Créez-en un</a></p>
        <p class="footer"> <a href="../index.php">Retourner à l'accueil</a></p>
    </div>
</div>
</body>
</html>
