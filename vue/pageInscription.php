<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../assets/css/pageInscription.css" rel="stylesheet">
    <title>Formulaire d'inscription</title>
</head>
<br>
<br>
<br>
<br>
<body>
<form action="../src/traitement/gestionInscription.php" method="POST">
    <h2><u>Inscription</u></h2>

    <div>
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
    </div>

    <div>
        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required>
    </div>

    <div>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div>
        <label for="mdp">Mot de passe :</label>
        <input type="password" id="mdp" name="mdp" minlength="2" required>
    </div>

    <div>
        <label for="passwordConfirm">Confirmer le mot de passe :</label>
        <input type="password" id="mdpC" name="mdpC" minlength="2" required>
    </div>

    <?php
    if (isset($_GET['parametre'])) {
        if ($_GET['parametre'] == 'mdp') {
            echo "<p style='color: red;'>❌ Les mots de passe ne correspondent pas.</p>";
       // } //elseif ($_GET['parametre'] == 'mdpCourt') {
           // echo "<p style='color: red;'>❌ Le mot de passe doit contenir au moins 12 caractères.</p>";
        } elseif ($_GET['parametre'] == 'doublon') {
            echo "<p style='color: red;'>❌ Un compte avec cet email existe déjà.</p>";
        } elseif ($_GET['parametre'] == 'champsVides') {
            echo "<p style='color: red;'>❌ Merci de remplir tous les champs.</p>";
        }
    }
    ?>

    <button type="submit">S'inscrire</button>
    <p class="footer" style="color: #555">Déjà membre ? <a href="pageConnexion.php">Connectez-vous</a></p>
    <p class="footer"> <a href="index.php">Retourner à l'accueil</a></p>
</form>
</body>
</html>
