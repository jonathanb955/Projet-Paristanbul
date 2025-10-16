<?php
use bdd\Bdd;
require '../bdd/Bdd.php'; // Connexion PDO

$bdd = new Bdd();
$pdo = $bdd->getBdd();

// Désactiver tout affichage avant la redirection
if (ob_get_level()) ob_clean();

if (isset($_POST['delete_offre'])) {
    $idOffre = intval($_POST['id_offre'] ?? 0);

    if ($idOffre > 0) {
        try {
            // Suppression de l’offre
            $stmt = $pdo->prepare("DELETE FROM offres_emplois WHERE id_offre = ?");
            $stmt->execute([$idOffre]);

            // (Optionnel) supprimer les candidatures associées
            $stmt2 = $pdo->prepare("DELETE FROM candidatures WHERE ref_offre = ?");
            $stmt2->execute([$idOffre]);

        } catch (Exception $e) {
            // Pour test : afficher l'erreur temporairement
            echo "Erreur SQL : " . $e->getMessage();
            exit;
        }
    } else {
        echo "ID d'offre invalide.";
        exit;
    }
}

// 🔁 Redirection après suppression (chemin relatif à partir de ce script)
header("Location: ../../vue/Admin/gestionOffreAdmin.php");
exit;
