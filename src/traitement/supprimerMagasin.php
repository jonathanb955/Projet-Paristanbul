<?php
// src/traitement/delete.php
declare(strict_types=1);

session_start();

use bdd\Bdd;

require_once __DIR__ . '/../bdd/Bdd.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

// Simple helper for redirecting back
function redirect_back(string $fallback = '../../vue/Admin/nosMagasinsAdmin.php') {
    $back = $_SERVER['HTTP_REFERER'] ?? $fallback;
    header('Location: ' . $back);
    exit;
}

// Get and sanitize input
$type = trim((string)($_POST['type'] ?? ''));
$id   = (int)($_POST['id'] ?? 0);

// Validate
if ($id <= 0 || $type === '') {
    $_SESSION['error_message'] = "Paramètres invalides pour la suppression.";
    redirect_back();
}

try {
    $bdd = new Bdd();
    $pdo = $bdd->getBdd();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Begin transaction
    $pdo->beginTransaction();

    if ($type === 'offre') {
        // If you want to remove candidatures attached to an offer as well:
        $stmtCandidates = $pdo->prepare("DELETE FROM candidatures WHERE ref_offre = ?");
        $stmtCandidates->execute([$id]);

        $stmt = $pdo->prepare("DELETE FROM offres_emplois WHERE id_offre = ?");
        $stmt->execute([$id]);

        $deleted = $stmt->rowCount();

        if ($deleted > 0) {
            $_SESSION['success_message'] = "Offre supprimée avec succès (et candidatures associées supprimées).";
        } else {
            $_SESSION['warning_message'] = "Aucune offre trouvée pour l'ID fourni.";
        }

    } elseif ($type === 'magasin') {
        // If you want to remove related data (be careful): example removes nothing else by default
        // Optionally remove related tables (stocks, horaires...) if present
        $stmt = $pdo->prepare("DELETE FROM magasins WHERE id_magasin = ?");
        $stmt->execute([$id]);

        $deleted = $stmt->rowCount();

        if ($deleted > 0) {
            $_SESSION['success_message'] = "Magasin supprimé avec succès.";
        } else {
            $_SESSION['warning_message'] = "Aucun magasin trouvé pour l'ID fourni.";
        }

    } else {
        // Unknown type -> rollback and exit
        $pdo->rollBack();
        $_SESSION['error_message'] = "Type de suppression non pris en charge.";
        redirect_back();
    }

    // Commit transaction
    $pdo->commit();

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Log error in production; show user-friendly message
    $_SESSION['error_message'] = "Erreur lors de la suppression : " . $e->getMessage();
}

// Redirect back to previous page (or a safe fallback)
redirect_back();
