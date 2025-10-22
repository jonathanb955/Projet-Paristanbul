<?php
session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

session_start();
$_SESSION['flash_success'] = "Vous êtes déconnecté(e).";

// redirection vers /vue/index.php
header('Location: /Projet-Paristanbul/vue/index.php');
exit;