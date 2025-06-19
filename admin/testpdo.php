<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=bdd_paristanbul;charset=utf8', 'root', ''); // adapte user/password
    echo "Connexion OK\n";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
?>
