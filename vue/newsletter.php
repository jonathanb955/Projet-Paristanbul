<?php
// newsletter.php
// Inscrit/MAJ un contact dans une liste Brevo. Répond en JSON.

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

try {
    /* ====== CONFIG ====== */
    // Active un retour d’erreur détaillé avec ?debug=1
    $DEBUG = isset($_GET['debug']) && $_GET['debug'] === '1';

    // Détection “local dev”
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $serverName = $_SERVER['SERVER_NAME'] ?? '';
    $isLocal = (
        stripos($host, 'localhost') !== false ||
        stripos($host, '127.0.0.1') !== false ||
        stripos($serverName, 'localhost') !== false ||
        stripos($serverName, '127.0.0.1') !== false
    );

    // Clé API (gardée telle quelle)
    $API_KEY = getenv('BREVO_API_KEY') ?: '';
    if ($API_KEY === '') {
        $API_KEY = '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';
    }

    // ID de la liste
    $LIST_ID = 3;

    if (!$LIST_ID) {
        http_response_code(500);
        echo json_encode(['ok'=>false,'msg'=>'Config liste manquante']);
        exit;
    }

    /* ====== SÉCURITÉ ORIGINE (souple) ====== */
    $enforceSameOrigin = true;
    if ($enforceSameOrigin) {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        // On refuse uniquement si un Origin est présent ET ne matche pas le host.
        // (Si Origin est vide, on laisse passer — typique en local.)
        if ($origin && strpos($origin, $host) === false) {
            http_response_code(403);
            echo json_encode(['ok'=>false,'msg'=>'Requête non autorisée']);
            exit;
        }
    }

    /* ====== MÉTHODE ====== */
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        http_response_code(405);
        echo json_encode(['ok'=>false,'msg'=>'Méthode non autorisée']);
        exit;
    }

    /* ====== DONNÉES ====== */
    // Le champ doit s’appeler "email" dans le formulaire
    $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
    $hp    = isset($_POST['_honey']) ? trim((string)$_POST['_honey']) : ''; // honeypot

    // Anti-bot : si le champ caché est rempli, on “réussit” silencieusement
    if ($hp !== '') {
        echo json_encode(['ok'=>true,'msg'=>'Merci']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(['ok'=>false,'msg'=>'Email invalide']);
        exit;
    }

    if ($API_KEY === '') {
        http_response_code(500);
        echo json_encode(['ok'=>false,'msg'=>'Clé API manquante']);
        exit;
    }

    /* ====== APPEL BREVO ====== */
    $payload = [
        'email'         => $email,
        'listIds'       => [$LIST_ID],
        'updateEnabled' => true,
    ];

    $ch = curl_init('https://api.brevo.com/v3/contacts');
    $curlOpts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'api-key: '.$API_KEY,
            'accept: application/json',
            'content-type: application/json',
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT => 15,
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, // utile si IPv6/DNS posent souci
    ];

    // En local uniquement : désactive la vérif SSL (DEV SEULEMENT)
    // Préfère configurer curl.cainfo en prod et enlève ces 2 lignes.
    if ($isLocal) {
        $curlOpts[CURLOPT_SSL_VERIFYPEER] = false;
        $curlOpts[CURLOPT_SSL_VERIFYHOST] = 0;
    }

    curl_setopt_array($ch, $curlOpts);

    $resp  = curl_exec($ch);
    $http  = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errNo = curl_errno($ch);
    $err   = curl_error($ch);
    curl_close($ch);

    if ($errNo) {
        error_log("[Brevo cURL] errno=$errNo error=$err");
        if ($DEBUG) {
            http_response_code(502);
            echo json_encode(['ok'=>false,'msg'=>'Erreur réseau','errno'=>$errNo,'error'=>$err]);
            exit;
        }
        http_response_code(502);
        echo json_encode(['ok'=>false,'msg'=>'Erreur réseau, réessayez.']);
        exit;
    }

    if (in_array($http, [200,201,204], true)) {
        echo json_encode(['ok'=>true,'msg'=>'Inscription prise en compte. Merci !']);
        exit;
    }

    // Si l’API renvoie une erreur, on tente d’extraire un message utile
    $j = json_decode((string)$resp, true);
    $m = is_array($j) ? ($j['message'] ?? ($j['code'] ?? "Erreur API (HTTP $http)")) : "Erreur API (HTTP $http)";
    error_log("Brevo error ($http): ".substr((string)$resp,0,1000));

    http_response_code($http ?: 400);
    echo json_encode(['ok'=>false,'msg'=>$m]);

} catch (Throwable $e) {
    error_log('newsletter.php fatal: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['ok'=>false,'msg'=>'Erreur interne']);
}
