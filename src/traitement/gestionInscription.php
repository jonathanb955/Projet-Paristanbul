<?php

var_dump($_POST);
$dsn = 'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8';
$bdd = new pdo($dsn, 'root', '');
$nom = "";
$prenom = "";
$email = "";
$role = "";
$mdp = "";
$confirmerMdp="";


$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$email = $_POST['email'];
$mdp = $_POST['mdp'];
$hashMdp = password_hash($mdp, PASSWORD_BCRYPT);
$confirmerMdp = $_POST['confirm'];

$role = "admin";

$sql = "INSERT  into utilisateurs ( nom , prenom , email ,mdp,role) 
values(:nom,:prenom,:email,:mdp,:role)";
$req = $bdd->prepare($sql);
$req->execute(array(
    'nom' => $nom,
    'prenom' => $prenom,
    'email' => $email,
    'mdp' => $hashMdp,
    'role' => $role,
));

echo "<p> Vos informations ont bien été enregistrées </p>";


?>
