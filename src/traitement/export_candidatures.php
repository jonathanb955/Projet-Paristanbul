<?php
// ============================
// candidatureAdmin.php
// ============================

// Connexion PDO
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

// ============================
// EXPORT CSV
// ============================
if (isset($_POST['export_csv'])) {

    $stmt = $pdo->query("SELECT c.*, o.titre_poste, o.ville
                         FROM candidatures c
                         LEFT JOIN offres_emplois o ON c.ref_offre = o.id_offre
                         ORDER BY c.date_candidature DESC");
    $candidatures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Headers CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=candidatures.csv');

    // BOM UTF-8 pour Excel
    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');

    // Entêtes CSV
    fputcsv($output, ['ID','Nom','Prénom','Email','Téléphone','Poste','Ville','Statut','Date candidature'], ',', '"', "\\");

    // Lignes CSV
    foreach ($candidatures as $c) {
        fputcsv($output, [
            $c['id'],
            $c['nom'],
            $c['prenom'],
            $c['email'],
            $c['telephone'],
            $c['titre_poste'] ?? '',
            $c['ville'] ?? '',
            $c['statut'],
            $c['date_candidature']
        ], ',', '"', "\\");
    }

    fclose($output);
    exit(); // STOP pour éviter tout HTML
}