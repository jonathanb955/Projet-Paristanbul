<?php
// contact_submit.php
header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
        exit;
    }

    // Récupération & mini validation
    $nom_complet = trim($_POST['nom'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $sujet       = trim($_POST['sujet'] ?? '');
    $message     = trim($_POST['message'] ?? '');

    if ($nom_complet === '' || $email === '' || $sujet === '' || $message === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Veuillez remplir tous les champs.']);
        exit;
    }

    // Connexion PDO
    $bdd = new PDO('mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insertion
    $sql = "INSERT INTO contacts (nom_complet, sujet, email, message) VALUES (?, ?, ?, ?)";
    $req = $bdd->prepare($sql);
    $ok  = $req->execute([$nom_complet, $sujet, $email, $message]);

    if (!$ok) {
        throw new Exception("Impossible d'enregistrer votre message.");
    }

    echo json_encode(['success' => true]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur serveur.']);
}
