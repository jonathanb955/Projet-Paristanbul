
<?php
var_dump($_POST);
$dsn = 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8';
$bdd = new pdo($dsn, 'root', '');
$login = "";
$mdp = "";

    $login = $_POST['email'];
    $mdp = $_POST['mdp'];
    $mdpUser="";
    $mdpAdmin="";

    $var = [$login];

    $sqlAdmin = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = ?  and role = 'admin' ");
    $sqlAdmin->execute($var);
    $lignesAdmin = $sqlAdmin->fetch();


    $sqlUser = $bdd->prepare("SELECT * from utilisateurs where  email = ?  and role ='utilisateur'");
    $sqlUser->execute($var);
    $ligneUser = $sqlUser->fetch();

if($ligneUser){
    if (password_verify($mdp,$ligneUser['mdp'])) {
        echo '<p>Connecté en tant que user</p>';
        echo'<p>Id : '.$ligneUser['nom'].'_'.$ligneUser['prenom'].'</p>';
    }
}
else if ($lignesAdmin) {
    if (password_verify($mdp, $lignesAdmin['mdp'])) {
        echo 'Connecté en tant que Admin </p>';
        echo'<p>Id : '.$lignesAdmin['nom'].'_'.$lignesAdmin['prenom'].'</p>';
    }
}else {
        echo 'MDP invalide';
    }



?>