<?php
/* =========================
   Nos Magasins — Paristanbul (upgrade seamless)
   ========================= */

// ====== MODE DEV (à adapter) ======
define('DEV_MODE', true);
define('DISABLE_FALLBACK_JS', false);

error_reporting(E_ALL);
ini_set('display_errors', DEV_MODE ? '1' : '0');

// Polyfill PHP 7.x
if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

/* --- Connexion PDO robuste (Mac/Windows) --- */
$pdo = null;
$connectedWith = null;
try {
    $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ];

    $dsnList = [
            'mysql:host=127.0.0.1;port=3306;dbname=bdd_paristanbul;charset=utf8mb4',
            'mysql:host=localhost;port=3306;dbname=bdd_paristanbul;charset=utf8mb4',
            'mysql:host=127.0.0.1;port=8889;dbname=bdd_paristanbul;charset=utf8mb4',
            'mysql:host=localhost;port=8889;dbname=bdd_paristanbul;charset=utf8mb4',
    ];
    $credList = [
            ['root',''],      // WAMP/XAMPP (Windows)
            ['root','root'],  // MAMP (Mac)
    ];

    $lastErr = null;
    foreach ($dsnList as $dsn) {
        foreach ($credList as [$user,$pass]) {
            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
                $connectedWith = $dsn.' | user='.$user;
                break 2;
            } catch (Throwable $e) {
                $lastErr = $e->getMessage();
            }
        }
    }
    if (!$pdo) {
        throw new RuntimeException('Impossible de se connecter à MySQL: '.$lastErr);
    }
} catch (Throwable $e) {
    error_log('DB error: '.$e->getMessage());
    if (DEV_MODE) {
        echo '<div style="background:#2b2b2b;color:#fff;padding:10px;border:1px solid #444;margin:10px 0">
                <strong>Erreur BDD:</strong> '.htmlspecialchars($e->getMessage()).'
              </div>';
    }
    $pdo = null;
}

/* --- coordonnées connues par ville (slug) --- */
$coords = [
        'nogent-sur-oise' => [49.278948, 2.464688],
        'villemomble'     => [48.8890,   2.5040],
        'bondy'           => [48.9022,   2.48278],
        'drancy'          => [48.924298, 2.445676],
        'villiers-le-bel' => [49.0094,   2.3911],
        'vert-saint-denis'=> [48.5707,   2.6296],
];

/* --- helpers --- */
function slugify($s) {
    $s = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
    $s = strtolower(preg_replace('~[^\pL\d]+~u','-',$s));
    return trim($s,'-');
}
function utf8_clean_array(&$arr) {
    array_walk_recursive($arr, function (&$v) {
        if (is_string($v)) {
            $v = preg_replace('/[^\PC\s]/u', '', $v);
            if (!mb_check_encoding($v, 'UTF-8')) {
                $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8, ISO-8859-1, ISO-8859-15, Windows-1252');
            }
        }
    });
}

/* --- build $magasins --- */
$magasins = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM magasins");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $villeRaw = trim((string)$row['ville_magasin']);

            // 1) Nettoyage
            $villeClean = preg_replace('~\s*\(.*?\)\s*~', '', $villeRaw);
            $villeClean = preg_replace('~\d.*$~', '', $villeClean);
            $villeClean = trim($villeClean);

            // 2) Slug
            $villeKey = preg_replace('~-+~','-', slugify($villeClean));

            // 3) Match coords "large"
            $keyMatch = null;
            foreach ($coords as $k => $_) {
                if ($villeKey === $k || str_contains($villeKey, $k) || str_contains($k, $villeKey)) {
                    $keyMatch = $k; break;
                }
            }
            // 4) Fallback: chercher la ville dans l’adresse complète
            if (!$keyMatch) {
                $hay = slugify(($row['rue'] ?? '').' '.($row['cp'] ?? '').' '.($row['ville_magasin'] ?? '').' '.($row['nom'] ?? ''));
                foreach ($coords as $k => $_) {
                    if (str_contains($hay, $k)) { $keyMatch = $k; break; }
                }
            }
            // 5) Coordonnées finales (Paris si inconnu)
            [$lat, $lon] = $keyMatch ? $coords[$keyMatch] : [48.8566, 2.3522];

            $ouverture = substr((string)$row['horaire_ouverture'], 0, 5);
            $fermeture = substr((string)$row['horaire_fermeture'], 0, 5);

            $magasins[] = [
                    "nom"      => "Paristanbul " . $villeClean,
                    "ville"    => $villeClean,
                    "cp"       => $row['cp'] ?? '',
                    "adresse"  => trim(($row['rue'] ?? '') . ", " . ($row['cp'] ?? '') . " " . $villeClean),
                    "tel"      => $row['num_tel'] ?? '',
                    "horaires" => ($row['jours_ouverture'] ?? '') . " : " . $ouverture . "–" . $fermeture,
                    "h_ouverture" => $ouverture,
                    "h_fermeture" => $fermeture,
                    "lat"      => (float)$lat,
                    "lon"      => (float)$lon,
                    "services" => array_values(array_filter([
                            "Boucherie",
                            "Épicerie",
                            !empty($row['parking']) ? "Parking" : null,
                            !empty($row['drive']) ? "Drive" : null
                    ])),
                    "image"    => $row['image'] ?? ''
            ];
        }
    } catch (Throwable $e) {
        error_log('SQL error: '.$e->getMessage());
    }
}

/* --- nettoyage + encodage JSON robuste --- */
utf8_clean_array($magasins);
$magasinsJson = json_encode(
        $magasins,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR
);
if ($magasinsJson === false) {
    error_log('JSON error: '.json_last_error_msg());
    $magasinsJson = '[]';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nos Magasins - Paristanbul</title>

    <link rel="stylesheet" href="../assets/css/index.css">
    <link rel="stylesheet" href="../assets/css/nosMagasins.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- MarkerCluster pour Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    <style>
        /* Soulignement dégradé au survol/actif — comme sur "Notre histoire" */
        :root{ --pi-blue:#2E4C97; --pi-red:#D6452E; } /* si pas déjà définies */

        .navbar .navbar-nav .nav-link{
            position: relative;
            font-weight: 700;
            opacity: .92;
            padding-bottom: .35rem;          /* petit espace pour la ligne */
        }
        .navbar .navbar-nav .nav-link:hover,
        .navbar .navbar-nav .nav-link:focus{
            opacity: 1;
        }

        /* la ligne animée */
        .navbar .navbar-nav .nav-link::after{
            content:"";
            position:absolute;
            left:50%;
            bottom:-6px;                     /* ajuste si besoin */
            width:0;
            height:2px;
            background:linear-gradient(90deg, var(--pi-blue), var(--pi-red));
            transition:width .25s, left .25s;
        }

        /* au survol ou quand le lien a .active */
        .navbar .navbar-nav .nav-link:hover::after,
        .navbar .navbar-nav .nav-link.active::after{
            width:100%;
            left:0;
        }
        /* Forcer couleur claire sur la nav */
        .navbar .nav-link { color: #e6e9f2 !important; font-weight: 600; opacity: .9; }
        .navbar .nav-link:hover, .navbar .nav-link:focus { color: #fff !important; opacity: 1; }
        .navbar .nav-link.active { color: #e21b3c !important; font-weight: 700; }

        :root{
            --ink:#E6E9F2; --muted:#9aa3b2; --card:#0F1524; --card-2:#141b2b;
            --pi-red:#e21b3c; --pi-green:#27ae60; --pi-blue:#2E4C97; --shadow:0 10px 30px rgba(0,0,0,.25);
        }
        body.pi-theme{ background:#0b1120; color:var(--ink); }
        a{ text-decoration:none }

        .navbar{ backdrop-filter: blur(6px); background:rgba(10,14,25,.4); border-bottom:1px solid rgba(255,255,255,.06) }
        .navbar .btn-dark{ background:var(--pi-red); border:none }

        .hero{
            position:relative; overflow:hidden; border-bottom:1px solid rgba(255,255,255,.06);
            background:
                    radial-gradient(1200px 600px at 20% -10%, rgba(255,0,76,.25), transparent 60%),
                    radial-gradient(1400px 800px at 120% 10%, rgba(46,76,151,.25), transparent 60%),
                    linear-gradient(180deg,#0d1426 0%, #0b1120 100%);
        }
        .hero .container{ padding:72px 0 }
        .hero h1{ font-weight:800; letter-spacing:.5px; }
        .hero p.lead{ color:var(--muted) }

        .kpis .card{
            background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
            border:1px solid rgba(255,255,255,.08); border-radius:16px; box-shadow:var(--shadow);
        }
        .kpi-number{ font-size:2rem; font-weight:800 }

        #locator{ padding:22px; background:var(--card-2); border:1px solid #1e2a44; border-radius:16px; }
        #map{ height:520px; border-radius:16px; border:1px solid #1e2a44; }
        .leaflet-container{ background:#0F1524; }
        .leaflet-control-zoom a{ background:#151e31; color:#fff; border:none; box-shadow:0 4px 14px rgba(0,0,0,.25) }
        .leaflet-bar a:hover{ background:#1b2436 }
        .leaflet-popup-content-wrapper,.leaflet-popup-tip{ background:#141b2b; color:#E6E9F2; border:none }

        .store-card{ background:var(--card); border:1px solid #1e2a44; border-radius:16px; transition: transform .18s ease, box-shadow .18s ease }
        .store-card:hover{ transform: translateY(-3px); box-shadow: 0 14px 40px rgba(0,0,0,.35) }
        .store-img{ height:164px; background:#0f1524; border-radius:12px; overflow:hidden }
        .store-img img{ width:100%; height:100%; object-fit:cover }
        .badge-pill{ border-radius:999px; padding:.35rem .6rem; font-weight:600 }
        .badge-purple{ background:#7b5cff; color:#fff }
        .badge-blue{ background:#2E4C97; color:#fff }
        .badge-green{ background:#2ecc71; color:#0b1f10 }
        .badge-amber{ background:#f1c40f; color:#2d2300 }

        .btn-outline{ border:2px solid rgba(255,255,255,.55); color:var(--ink); padding:.55rem .9rem; border-radius:12px; background:transparent; font-weight:700 }
        .btn-outline.dark{ background:var(--pi-red); border-color:var(--pi-red); color:#fff }

        .skeleton{ position:relative; overflow:hidden; background:#10172a; border-radius:12px; min-height:164px }
        .skeleton::after{ content:""; position:absolute; inset:0; background:linear-gradient(90deg, transparent, rgba(255,255,255,.06), transparent);
            animation:shimmer 1.4s infinite; }
        @keyframes shimmer{ 0%{transform:translateX(-100%)} 100%{transform:translateX(100%)} }

        .highlight-marker{ filter: drop-shadow(0 0 8px rgba(226,27,60,.9)); }

        @media (min-width:992px){
            .sticky-map{ position:sticky; top:16px }
        }
    </style>

    <!-- ===== Overrides SEAMLESS pour effacer toutes les "boîtes" visibles ===== -->
    <style id="seamless-overrides">
        /* Unifie les surfaces */
        :root{ --card:#0b1120; --card-2:#0b1120; }
        body.pi-theme{ background:#0b1120 !important; }

        /* Navbar & Hero fondus */
        .navbar{ background:transparent !important; border:none !important; box-shadow:none !important; }
        .hero{
            border:none !important;
            padding-bottom:32px !important;
            background:#0b1120 !important; /* coupe le dégradé pour supprimer la marche */
        }

        /* Zone liste + carte */
        #locator{ background:transparent !important; border:none !important; box-shadow:none !important; padding:0 !important; margin-top:-10px !important; }

        /* Leaflet plat */
        #map{ border:none !important; box-shadow:none !important; border-radius:16px !important; }
        .leaflet-container{ background:#0b1120 !important; }
        .leaflet-popup-content-wrapper,.leaflet-popup-tip{ background:#0b1120 !important; border:none !important; box-shadow:none !important; }
        .leaflet-control-zoom a{ background:#121a2a !important; border:none !important; box-shadow:none !important; }

        /* Cartes magasin ultra light */
        .store-card{ background:transparent !important; border:none !important; box-shadow:none !important; }
        .store-img{ background:#101626 !important; border:none !important; border-radius:16px !important; }

        /* KPI lissés */
        .kpis .card{ background:transparent !important; border:none !important; box-shadow:none !important; }

        /* Boutons/inputs unifiés */
        .btn-outline, .btn.btn-outline-light, .btn.btn-outline-secondary{
            background:transparent !important; border-color:rgba(255,255,255,.18) !important;
        }
        .btn-outline:hover, .btn.btn-outline-light:hover, .btn.btn-outline-secondary:hover{
            background:rgba(255,255,255,.06) !important;
        }
        .input-group .input-group-text{ background:#121a2a !important; border-color:#121a2a !important; }
        #searchInput.form-control, #sortSelect{ background:#121a2a !important; border-color:#121a2a !important; color:#e6e9f2 !important; }

        /* Skeletons plus neutres */
        .skeleton{ background:#101626 !important; box-shadow:none !important; }
        .skeleton::after{ opacity:.5 !important; }

        /* Badges état */
        .store-card .badge.bg-success{ background:#2ecc71 !important; box-shadow:none !important; }
        .store-card .badge.bg-secondary{ background:#2E4C97 !important; }
    </style><style id="hairlines">
        :root{
            --hair: rgba(255,255,255,.08);  /* ligne très légère */
            --hair-strong: rgba(255,255,255,.14);
        }

        /* 1) NAVBAR : trait fin en bas, comme sur l’index */
        .navbar{
            background:transparent !important;
            border:none !important;
            box-shadow: none !important;
            position:relative;
        }
        .navbar::after{
            content:""; position:absolute; left:0; right:0; bottom:0; height:1px;
            background: linear-gradient(90deg, transparent, var(--hair), transparent);
            pointer-events:none;
        }

        /* 2) CARTES MAGASINS : pas de “boîte” — juste un filet 1px arrondi */
        .store-card{
            background:transparent !important;
            border:1px solid var(--hair) !important;
            border-radius:16px !important;
            box-shadow:none !important;
            transition:border-color .18s ease, transform .18s ease;
        }
        .store-card:hover{
            border-color: var(--hair-strong) !important;
            transform: translateY(-2px);
        }
        /* image inchangée */
        .store-img{ border-radius:12px; overflow:hidden; position:relative; }

        /* 3) MAP : filet fin autour de la carte seulement */
        #map{
            border:1px solid var(--hair) !important;
            border-radius:16px !important;
            box-shadow:none !important;
        }

        /* 4) BARRES OUTILS (recherche / filtres) : contour discret */
        .input-group .input-group-text,
        #searchInput.form-control,
        #sortSelect,
        .btn.btn-outline-light,
        .btn.btn-outline-secondary{
            border:1px solid var(--hair) !important;
            background:rgba(255,255,255,.02) !important;
        }
        .btn.btn-outline-light:hover,
        .btn.btn-outline-secondary:hover{
            border-color:var(--hair-strong) !important;
            background:rgba(255,255,255,.05) !important;
        }

        /* 5) KPI : petites “dalles” fines sans gros fond */
        .kpis .card{
            background:transparent !important;
            border:1px solid var(--hair) !important;
            box-shadow:none !important;
            border-radius:16px !important;
        }

        /* 6) Séparateurs horizontaux très fins entre groupes (optionnel) */
        .section-hairline{
            position:relative;
            padding-top:24px;
            margin-top:8px;
        }
        .section-hairline::before{
            content:""; position:absolute; left:0; right:0; top:0; height:1px;
            background: linear-gradient(90deg, transparent, var(--hair), transparent);
        }

        /* 7) Pastille Ouvert/Fermé en overlay (si tu as activé le patch précédent) */
        .store-status{
            position:absolute; top:10px; left:10px; z-index:2;
            border-radius:999px; padding:.35rem .6rem; font-weight:700; font-size:.8rem;
            box-shadow:0 4px 18px rgba(0,0,0,.28);
        }
        .store-status.open{ background:#2ecc71; color:#0b1f10; }
        .store-status.closed{ background:#2E4C97; color:#fff; }

        /* 8) Petits raffinements */
        .leaflet-control-zoom a{ border:1px solid var(--hair) !important; }
        .badge-pill{ border:1px solid var(--hair); }
    </style>
    <style id="dark-animated-bg">
        :root{
            /* Ajuste l’intensité ici si besoin */
            --bg-base: #080d18;      /* plus sombre que #0b1120 */
            --bg-ink:  #e6e9f2;
            --blob-a: rgba(226,27,60,.10);   /* rouge PI très léger */
            --blob-b: rgba(46,76,151,.12);   /* bleu PI très léger */
            --blob-c: rgba(39,174,96,.08);   /* vert PI très léger */
        }

        /* Fond global plus sombre */
        body.pi-theme{
            background: var(--bg-base) !important;
            color: var(--bg-ink);
        }

        /* Couche animée très discrète derrière tout */
        body.pi-theme::before{
            content:"";
            position: fixed;
            inset: -20vmax;               /* dépasse l’écran pour éviter les bords visibles */
            z-index: -1;                  /* derrière tout */
            pointer-events: none;
            filter: blur(60px);           /* halo doux */
            opacity: .75;                 /* force globale de l’animation */
            background:
                    radial-gradient(35vmax 35vmax at 15% 10%, var(--blob-a), transparent 60%),
                    radial-gradient(45vmax 45vmax at 85% 20%, var(--blob-b), transparent 65%),
                    radial-gradient(40vmax 40vmax at 50% 95%, var(--blob-c), transparent 60%);
            animation: paristanbul-float 28s linear infinite;
            transform: translateZ(0);     /* hint GPU */
        }

        /* Variation lente de positions pour un effet vivant */
        @keyframes paristanbul-float{
            0%   { transform: translate3d(0, 0, 0) rotate(0deg) scale(1); }
            50%  { transform: translate3d(-2%, 1%, 0) rotate(6deg)  scale(1.03); }
            100% { transform: translate3d(0, 0, 0) rotate(0deg)    scale(1); }
        }

        /* Pas d’animation si l’utilisateur préfère réduire les mouvements */
        @media (prefers-reduced-motion: reduce){
            body.pi-theme::before{ animation: none; opacity: .55; }
        }

        /* Renforce un chouïa le contraste des hairlines si tu as collé le patch précédent */
        :root{
            --hair: rgba(255,255,255,.10);
            --hair-strong: rgba(255,255,255,.18);
        }

        /* Léger assombrissement du hero pour qu’il “fonde” avec le fond */
        .hero{
            background: linear-gradient(180deg, rgba(0,0,0,.18), transparent 30%) !important;
        }

        /* Petites touches pour garder une bonne lisibilité */
        .store-img{ background:#0b1324 !important; }
        .leaflet-container{ background:#0b1324 !important; }
    </style>
    <style id="pi-orbs-css">
        :root{
            /* fond plus sombre + hairlines un poil plus visibles */
            --bg-base:#010309;
            --hair:rgba(255,255,255,.12);
            --hair-strong:rgba(255,255,255,.20);
            /* orbes (basse opacité, très “glass”) */
            --orb-blue:rgba(46,76,151,.18);
            --orb-red: rgba(226,27,60,.16);
        }

        /* fond global encore plus dark */
        body.pi-theme{ background:var(--bg-base) !important; }

        /* calque des orbes : pleine page, derrière tout */
        .pi-orbs{
            position:fixed; inset:0; z-index:-1; pointer-events:none;
            overflow:hidden;
        }
        .pi-orbs .orb{
            position:absolute; width:48vmax; height:48vmax; border-radius:9999px;
            filter: blur(80px);
            opacity:.75;                       /* plus de transparence => baisse si besoin */
            will-change: transform;
            mix-blend-mode: screen;            /* rendu doux sur fond sombre */
        }
        .pi-orbs .orb-blue{ background:var(--orb-blue); }
        .pi-orbs .orb-red { background:var(--orb-red);  }

        /* positions de départ (coins / centre bas) */
        .pi-orbs .a{ top:-10vmax; left:-6vmax;  animation:orb-drift-a 36s linear infinite; }
        .pi-orbs .b{ top:-8vmax; right:-10vmax; animation:orb-drift-b 42s linear infinite; }
        .pi-orbs .c{ bottom:-12vmax; left:15vw; animation:orb-drift-c 40s linear infinite; width:42vmax; height:42vmax; }
        .pi-orbs .d{ bottom:-14vmax; right:10vw; animation:orb-drift-d 46s linear infinite; width:50vmax; height:50vmax; }

        /* animations (lentes, subtiles) */
        @keyframes orb-drift-a{
            0%   { transform:translate3d(0,0,0) scale(1); }
            50%  { transform:translate3d(4vw,2vh,0) scale(1.05); }
            100% { transform:translate3d(0,0,0) scale(1); }
        }
        @keyframes orb-drift-b{
            0%   { transform:translate3d(0,0,0) scale(1); }
            50%  { transform:translate3d(-3vw,3vh,0) scale(1.03); }
            100% { transform:translate3d(0,0,0) scale(1); }
        }
        @keyframes orb-drift-c{
            0%   { transform:translate3d(0,0,0) scale(1); }
            50%  { transform:translate3d(2vw,-2vh,0) scale(1.06); }
            100% { transform:translate3d(0,0,0) scale(1); }
        }
        @keyframes orb-drift-d{
            0%   { transform:translate3d(0,0,0) scale(1); }
            50%  { transform:translate3d(-2vw,-3vh,0) scale(1.04); }
            100% { transform:translate3d(0,0,0) scale(1); }
        }

        /* réduit encore le fond des sections pour le “plus sombre & transparent” */
        .hero{ background:linear-gradient(180deg, rgba(0,0,0,.28), transparent 35%) !important; }
        #locator, .kpis .card, .store-card, #map{
            background:transparent !important;
            /* hairlines conservées par tes règles existantes */
        }

        /* accessibilité : pas d’animation si motion réduit */
        @media (prefers-reduced-motion: reduce){
            .pi-orbs .orb{ animation:none; opacity:.55; }
        }
    </style>
    <style id="pi-animated-word">
        /* Dégradé rouge ⇄ bleu animé sur le texte (compatifble Safari) */
        .pi-word-anim{
            --c1:#e21b3c;   /* rouge PI */
            --c2:#2E4C97;   /* bleu PI */
            background-image: linear-gradient(90deg, var(--c1), var(--c2), var(--c1));
            background-size: 200% 100%;
            background-position: 0% 50%;

            -webkit-background-clip: text;  /* Safari/iOS */
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;

            display:inline-block;            /* pour animer le background proprement */
            animation: piWordShift 6s ease-in-out infinite alternate;
        }
        .pi-word-anim.glow{
            /* halo très léger pour le contraste */
            text-shadow: 0 0 12px rgba(226,27,60,.15), 0 0 14px rgba(46,76,151,.12);
        }
        @keyframes piWordShift{
            0%   { background-position:   0% 50%; filter:saturate(110%); }
            50%  { background-position: 100% 50%; filter:saturate(130%); }
            100% { background-position:   0% 50%; filter:saturate(110%); }
        }
        @media (prefers-reduced-motion: reduce){
            .pi-word-anim{ animation:none; }
        }
    </style>
    <style id="pi-marquee-cities">
        .pi-marquee{position:sticky;top:0;z-index:60;overflow:hidden;
            border-bottom:1px solid rgba(255,255,255,.08);background:transparent}
        .pi-marquee__inner{display:flex;gap:40px;padding:10px 0;white-space:nowrap;
            animation:piMarquee 22s linear infinite}
        .pi-pill{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;
            border-radius:999px;background:linear-gradient(145deg,#101733,#111621);
            border:1px solid #1a2340;font-size:.92rem}
        .pi-pill .pi-dot{width:8px;height:8px;border-radius:50%;
            background:conic-gradient(from 90deg,#e21b3c,#2E4C97)}
        @keyframes piMarquee{from{transform:translateX(0)} to{transform:translateX(-50%)}}
        @media (prefers-reduced-motion:reduce){ .pi-marquee__inner{animation:none} }
        /* bannière (déjà présente) */
        .pi-marquee{
            position: sticky; top: 0; z-index: 60; overflow: hidden;
            border-bottom:1px solid rgba(255,255,255,.08);
            background: transparent;

            /* transition pour un slide propre */
            transition: transform .25s ease, opacity .25s ease;
        }

        /* état masqué */
        .pi-marquee.is-hidden{
            transform: translateY(-100%);
            opacity: 0;
            pointer-events: none;
        }
    </style>

</head>
<body class="pi-theme">
<div class="pi-orbs" aria-hidden="true">
    <span class="orb orb-blue a"></span>
    <span class="orb orb-red  b"></span>
    <span class="orb orb-blue c"></span>
    <span class="orb orb-red  d"></span>
</div>
<div class="pi-marquee" aria-hidden="true">
    <div class="pi-marquee__inner">
        <!-- 4 premiers -->
        <span class="pi-pill"><span class="pi-dot"></span> Bondy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Drancy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villemomble</span>
        <span class="pi-pill"><span class="pi-dot"></span> Vert-Saint-Denis</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villiers-le-Bel</span>
        <span class="pi-pill"><span class="pi-dot"></span> Nogent-Sur-Oise</span>
        <!-- 4 suivants (on répète pour la boucle continue) -->
        <span class="pi-pill"><span class="pi-dot"></span> Bondy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Drancy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villemomble</span>
        <span class="pi-pill"><span class="pi-dot"></span> Vert-Saint-Denis</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villiers-le-Bel</span>
        <span class="pi-pill"><span class="pi-dot"></span> Nogent-Sur-Oise</span>
    </div>
</div>
<!-- le reste de ta page -->

<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul" style="height:48px">
            </a>

            <!-- Burger mobile -->
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list" style="font-size:1.6rem"></i>
            </button>

            <!-- Nav centrée + boutons à droite -->
            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav mx-lg-auto my-3 my-lg-0 gap-lg-2">
                    <li class="nav-item"><a class="nav-link px-3" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="postuler.php">Postuler</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="quiSommesNous.html">Notre histoire</a></li>
                </ul>

                <div class="d-flex gap-2 ms-lg-0 ms-auto">
                    <a href="pageInscription.php" class="btn btn-light">Inscription</a>
                    <a href="pageConnexion.php" class="btn btn-dark">Connexion</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<section class="hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="display-5">
                    Trouvez votre <span class="pi-word-anim glow">Paristanbul</span> le plus proche
                </h1>                <p class="lead mb-3">Boucheries &amp; épiceries de qualité, partout près de vous.</p>
                <div class="d-flex gap-2">
                    <button id="locateMe" class="btn-outline dark"><i class="bi bi-crosshair"></i> Me localiser</button>
                    <a href="#locator" class="btn-outline"><i class="bi bi-map"></i> Voir la carte</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="kpis row g-3">
                    <div class="col-6">
                        <div class="card p-3 text-center">
                            <div class="kpi-number" id="kpiStores">—</div>
                            <div class="text-muted">Magasins</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3 text-center">
                            <div class="kpi-number" id="kpiDepartments">—</div>
                            <div class="text-muted">Départements servis</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card p-3 text-center">
                            <div class="kpi-number" id="kpiOpenNow">—</div>
                            <div class="text-muted">Ouverts maintenant</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<main class="container my-4" id="locator">
    <div class="row g-3">
        <div class="col-lg-5 order-lg-2">
            <div class="sticky-map">
                <div class="d-flex gap-2 align-items-center mb-2">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input id="searchInput" type="text" class="form-control border-start-0" placeholder="Ville, code postal, adresse...">
                    </div>
                    <button id="btnFilter" class="btn btn-outline-secondary"><i class="bi bi-funnel"></i></button>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <button class="btn btn-sm btn-outline-light" data-filter="all">Tous</button>
                    <button class="btn btn-sm btn-outline-light" data-filter="Boucherie">Boucherie</button>
                    <button class="btn btn-sm btn-outline-light" data-filter="Épicerie">Épicerie</button>
                    <button class="btn btn-sm btn-outline-light" data-filter="Drive">Drive</button>
                    <button class="btn btn-sm btn-outline-light" data-filter="Parking">Parking</button>
                    <div class="ms-auto">
                        <select id="sortSelect" class="form-select form-select-sm">
                            <option value="default">Tri : recommandé</option>
                            <option value="distance">Plus proche</option>
                            <option value="name">Nom A→Z</option>
                            <option value="open">Ouverts d’abord</option>
                        </select>
                    </div>
                </div>
                <div id="map" aria-label="Carte des magasins"></div>
            </div>
        </div>

        <div class="col-lg-7 order-lg-1">
            <div id="list" class="row g-3">
                <!-- Skeletons au chargement -->
                <?php for($i=0;$i<6;$i++): ?>
                    <div class="col-md-6"><div class="skeleton"></div></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</main>

<footer class="container my-5 text-center text-muted">
    © <?= date('Y') ?> Paristanbul — Tous droits réservés
</footer>

<script>
    /* ===== Données PHP -> JS ===== */
    const magasins = (() => { try { return <?php echo ($magasinsJson ?: '[]'); ?>; } catch(_) { return []; } })();

    /* ===== Fallback si BDD vide ===== */
    const FALLBACK = [
        { nom:"Paristanbul Villiers-le-Bel", ville:"Villiers-le-Bel", cp:"95400", adresse:"117 Avenue Pierre Semard, 95400 Villiers-le-Bel", tel:"+33 7 49 82 61 33", h_ouverture:"08:30", h_fermeture:"20:00", horaires:"Lun-Dim : 08:30–20:00", lat:49.0094, lon:2.3911, services:["Boucherie","Épicerie","Parking"], image:"../assets/img/magasins/vlb.jpg" },
        { nom:"Paristanbul Bondy",          ville:"Bondy", cp:"93140", adresse:"116 Avenue Gallieni, 93140 Bondy",                 tel:"+33 7 49 82 61 33", h_ouverture:"08:30", h_fermeture:"20:00", horaires:"Lun-Dim : 08:30–20:00", lat:48.9022, lon:2.48278, services:["Boucherie","Épicerie"], image:"../assets/img/magasins/bondy.jpg" },
        { nom:"Paristanbul Drancy",         ville:"Drancy", cp:"93700", adresse:"83 Avenue Marceau, 93700 Drancy",                  tel:"+33 7 49 82 61 33", h_ouverture:"08:30", h_fermeture:"20:30", horaires:"Lun-Dim : 08:30–20:30", lat:48.924298, lon:2.445676, services:["Boucherie","Épicerie","Drive"], image:"../assets/img/magasins/drancy.jpg" }
    ];

    const LIST_ORIG = (Array.isArray(magasins) && magasins.length) ? magasins : FALLBACK;

    /* ===== Utils ===== */
    const toMinutes = (hhmm)=>{ const [h,m]=String(hhmm||"").split(":").map(n=>parseInt(n||0,10)); return h*60+(m||0); };
    const nowMins = ()=>{ const d = new Date(); return d.getHours()*60 + d.getMinutes(); };
    const isOpenNow = (m)=>{ const o=toMinutes(m.h_ouverture), f=toMinutes(m.h_fermeture), n=nowMins(); return (o<f) ? (n>=o && n<=f) : (n>=o || n<=f); };
    const km = (a,b,c,d)=>{ const R=6371, rad=x=>x*Math.PI/180, dLat=rad(c-a), dLon=rad(d-b); const A=Math.sin(dLat/2)**2 + Math.cos(rad(a))*Math.cos(rad(c))*Math.sin(dLon/2)**2; return (R*2*Math.atan2(Math.sqrt(A),Math.sqrt(1-A))); };
    const formatKm = v => (v<1 ? (v*1000|0)+' m' : v.toFixed(1)+' km');
    const itineraireURL = (m, origin)=> {
        const dest = `${m.lat},${m.lon}`;
        let url = `https://www.google.com/maps/dir/?api=1&destination=${dest}`;
        if (origin) url += `&origin=${origin.lat},${origin.lon}`;
        return url;
    };
    const telLink = (t)=>`tel:${(t||'').replace(/\s+/g,'')}`;

    /* ===== État ===== */
    let userPos = null;
    let LIST = LIST_ORIG.map((m,i)=>({...m, id:i}));

    /* ===== KPI ===== */
    function setKPIs(list){
        document.getElementById('kpiStores').textContent = list.length;
        const deps = new Set(list.map(m=>String(m.cp||'').slice(0,2)).filter(Boolean));
        document.getElementById('kpiDepartments').textContent = deps.size || "—";
        document.getElementById('kpiOpenNow').textContent = list.filter(isOpenNow).length;
    }

    /* ===== Carte Leaflet + clusters ===== */
    let map, clusterLayer, markersById = {};
    function initMap(){
        map = L.map('map', { zoomControl:true }).setView([46.6031, 1.8883], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'© OpenStreetMap' }).addTo(map);
        clusterLayer = L.markerClusterGroup({ showCoverageOnHover:false, maxClusterRadius:48 });
        map.addLayer(clusterLayer);
        renderMarkers(LIST);
        setTimeout(()=>map.invalidateSize(), 150);
    }

    function markerIcon(open){
        const color = open ? '#e21b3c' : '#2E4C97';
        const svg = encodeURIComponent(`<svg xmlns='http://www.w3.org/2000/svg' width='44' height='44' viewBox='0 0 44 44'><path fill='${color}' d='M22 2c8.8 0 16 7.2 16 16 0 11-16 24-16 24S6 29 6 18C6 9.2 13.2 2 22 2z'/><circle cx='22' cy='18' r='6' fill='white'/></svg>`);
        return L.icon({ iconUrl:`data:image/svg+xml,${svg}`, iconSize:[44,44], iconAnchor:[22,40], popupAnchor:[0,-36] });
    }

    function renderMarkers(list){
        clusterLayer.clearLayers(); markersById = {};
        list.forEach(m=>{
            const mk = L.marker([m.lat, m.lon], { icon: markerIcon(isOpenNow(m)) })
                .bindPopup(`<strong>${m.nom}</strong><br>${m.adresse}<br><small>${m.horaires}</small>`);
            mk.on('click', ()=> {
                const el = document.querySelector(`[data-store="${m.id}"]`);
                if (el){ el.scrollIntoView({behavior:'smooth', block:'center'}); el.classList.add('ring'); setTimeout(()=>el.classList.remove('ring'), 800); }
            });
            clusterLayer.addLayer(mk);
            markersById[m.id]=mk;
        });
    }

    /* ===== Rendu cartes ===== */
    function serviceBadge(s){
        let cls='badge-pill bg-secondary';
        if (s==='Boucherie') cls='badge-pill badge-purple';
        else if (s==='Épicerie') cls='badge-pill badge-blue';
        else if (s==='Drive') cls='badge-pill badge-green';
        else if (s==='Parking') cls='badge-pill badge-amber';
        return `<span class="${cls} me-1 mb-1 d-inline-block">${s}</span>`;
    }

    function cardHTML(m, dist){
        const open = isOpenNow(m);
        const distance = dist!=null ? `<span class="text-muted"><i class="bi bi-person-walking"></i> ${formatKm(dist)}</span>` : '';
        const tel = m.tel ? `<a class="btn btn-outline-light btn-sm" href="${telLink(m.tel)}"><i class="bi bi-telephone"></i> Appeler</a>` : '';

        return `
  <div class="col-md-6">
    <article class="store-card p-3" tabindex="0" data-store="${m.id}">
      <div class="store-img mb-3">
        <span class="store-status ${open?'open':'closed'}">${open?'Ouvert':'Fermé'}</span>
        ${m.image ? `<img src="${m.image}" loading="lazy" alt="${m.nom}">` : `<div class="skeleton"></div>`}
      </div>

      <div class="d-flex align-items-start justify-content-between mb-1">
        <h5 class="mb-0">${m.nom}</h5>
        <!-- on laisse une classe au cas où, mais masquée par CSS -->
        <span class="badge ${open?'bg-success':'bg-secondary'} header-status">${open?'Ouvert':'Fermé'}</span>
      </div>

      <div class="small text-muted mb-2"><i class="bi bi-geo-alt-fill text-danger"></i> ${m.adresse}</div>
      <div class="small mb-2"><i class="bi bi-clock text-danger"></i> ${m.horaires}</div>
      <div class="mb-2">${(m.services||[]).map(serviceBadge).join(' ')}</div>

      <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-danger btn-sm" href="${itineraireURL(m, userPos)}" target="_blank" rel="noopener">
          <i class="bi bi-geo-fill"></i> Itinéraire
        </a>
        ${tel}
        <button class="btn btn-outline-light btn-sm" data-copy="${m.adresse}"><i class="bi bi-clipboard"></i> Copier l’adresse</button>
        <button class="btn btn-outline-light btn-sm" data-share='${JSON.stringify({title:m.nom,text:m.adresse,url:itineraireURL(m,userPos)})}'>
          <i class="bi bi-share"></i> Partager
        </button>
        <span class="ms-auto small">${distance}</span>
      </div>
    </article>
  </div>`;
    }

    function renderList(list){
        const wrap = document.getElementById('list');
        wrap.innerHTML = list.map(m=>{
            let d=null; if (userPos) d = km(userPos.lat,userPos.lon,m.lat,m.lon);
            return cardHTML(m, d);
        }).join('');
        // hover sync -> highlight marker
        wrap.querySelectorAll('[data-store]').forEach(el=>{
            el.addEventListener('mouseenter', ()=>{
                const id = el.getAttribute('data-store'); const mk = markersById[id];
                if (mk){ const iconEl = mk._icon; if (iconEl){ iconEl.classList.add('highlight-marker'); setTimeout(()=>iconEl.classList.remove('highlight-marker'), 700); } }
            });
            el.addEventListener('click', ()=>{
                const id = el.getAttribute('data-store'), m = LIST.find(x=>x.id==id);
                if (m && markersById[id]){ map.flyTo([m.lat,m.lon], 14, {duration:.6}); markersById[id].openPopup(); }
            });
        });
        // copy/share
        wrap.querySelectorAll('[data-copy]').forEach(btn=>{
            btn.addEventListener('click', async ()=>{
                try { await navigator.clipboard.writeText(btn.getAttribute('data-copy')); btn.innerHTML='<i class="bi bi-clipboard-check"></i> Copié !'; setTimeout(()=>btn.innerHTML='<i class="bi bi-clipboard"></i> Copier l’adresse',1400); } catch{}
            });
        });
        wrap.querySelectorAll('[data-share]').forEach(btn=>{
            btn.addEventListener('click', async ()=>{
                try{
                    const payload = JSON.parse(btn.getAttribute('data-share'));
                    if (navigator.share) await navigator.share(payload);
                    else window.open(payload.url, '_blank','noopener');
                }catch{}
            });
        });
    }

    /* ===== Recherche + filtres + tri ===== */
    let activeFilter = 'all';
    function applySearchFilterSort(){
        const q = (document.getElementById('searchInput').value||'').toLowerCase().trim();
        let out = LIST_ORIG.filter(m=>{
            const hay = [m.nom,m.ville,m.cp,m.adresse].join(' ').toLowerCase();
            const hit = !q || hay.includes(q);
            const fit = (activeFilter==='all') || (m.services||[]).includes(activeFilter);
            return hit && fit;
        });

        const sort = document.getElementById('sortSelect').value;
        if (sort==='name') out.sort((a,b)=>a.nom.localeCompare(b.nom));
        else if (sort==='open') out.sort((a,b)=> (isOpenNow(b)-isOpenNow(a)) || a.nom.localeCompare(b.nom));
        else if (sort==='distance' && userPos) out.sort((a,b)=> km(userPos.lat,userPos.lon,a.lat,a.lon) - km(userPos.lat,userPos.lon,b.lat,b.lon));

        LIST = out.map((m,i)=>({...m, id:m.id ?? i}));
        renderMarkers(LIST);
        renderList(LIST);
        setKPIs(LIST);
    }

    /* ===== Geolocate ===== */
    document.getElementById('locateMe').addEventListener('click', ()=>{
        if (!navigator.geolocation) { alert("Votre navigateur ne supporte pas la géolocalisation."); return; }
        navigator.geolocation.getCurrentPosition(pos=>{
            userPos = {lat:pos.coords.latitude, lon:pos.coords.longitude};
            map.setView([userPos.lat, userPos.lon], 11);
            L.marker([userPos.lat, userPos.lon]).addTo(map).bindPopup("📍 Vous êtes ici").openPopup();
            document.getElementById('sortSelect').value='distance';
            applySearchFilterSort();
        }, ()=> alert("Impossible de vous localiser."), { enableHighAccuracy:true, timeout:8000, maximumAge:0 });
    });

    /* ===== Listeners UI ===== */
    document.getElementById('searchInput').addEventListener('input', ()=> applySearchFilterSort());
    document.querySelectorAll('[data-filter]').forEach(btn=>{
        btn.addEventListener('click', ()=>{
            activeFilter = btn.getAttribute('data-filter');
            document.querySelectorAll('[data-filter]').forEach(b=>b.classList.remove('btn-light'));
            btn.classList.add('btn-light');
            applySearchFilterSort();
        });
    });
    document.getElementById('sortSelect').addEventListener('change', ()=> applySearchFilterSort());

    /* ===== Boot ===== */
    document.addEventListener('DOMContentLoaded', ()=>{
        setKPIs(LIST_ORIG);
        initMap();
        applySearchFilterSort();
    });
</script>
<!-- … tes autres <script> au-dessus … -->

<script>
    (() => {
        const viewport = document.querySelector('.pi-marquee');
        const track    = document.querySelector('.pi-marquee__inner');
        if(!viewport || !track) return;

        const BASE_HTML = track.innerHTML;   // une seule série d’items

        function build() {
            track.style.animation = 'none';
            track.innerHTML = BASE_HTML;

            // répète jusqu’à couvrir au moins 100% du viewport
            while (track.scrollWidth < viewport.clientWidth) {
                track.insertAdjacentHTML('beforeend', BASE_HTML);
            }
            // duplique l’ensemble pour boucler sans trou
            const oneSet = track.innerHTML;
            track.innerHTML = oneSet + oneSet;

            requestAnimationFrame(() => { track.style.animation = ''; });
        }



        let t; const onResize = () => { clearTimeout(t); t = setTimeout(build, 120); };
        build();
        window.addEventListener('resize', onResize);
    })();
</script>

<style>
    .pi-marquee{overflow:hidden}
    .pi-marquee__inner{display:flex;gap:40px;white-space:nowrap;will-change:transform;
        animation:piMarquee var(--marquee-speed,22s) linear infinite}
    @keyframes piMarquee{from{transform:translateX(0)} to{transform:translateX(-50%)}}
</style>

<style>

    /* Lisibilité KPIs + adresses */
    .hero .kpis .card, .hero .kpis .card *{ color:#fff !important; }
    .hero .kpis .card .text-muted{ color:#e8edf7 !important; opacity:1 !important; }
    .hero .kpis .card .kpi-number{ font-size:2.6rem; font-weight:900; color:#fff !important; text-shadow:0 0 12px rgba(0,0,0,.35); }

    .store-card .text-muted, .magasin-card .text-muted, .store-card .small, .magasin-card .small{
        color:#dfe6f5 !important; opacity:1 !important; font-size:0.95rem; line-height:1.4;
    }
    .store-card .bi-geo-alt-fill, .magasin-card .bi-geo-alt-fill{ color:#ff4757 !important; }
    .store-card .badge.bg-success, .magasin-card .badge.bg-success{ background:#27ae60 !important; box-shadow:0 0 6px rgba(39,174,96,.6); }
    .store-card .badge.bg-secondary, .magasin-card .badge.bg-secondary{ background: #cc2e40 !important; color:#fff !important; }
    /* --- Pastille statut en overlay sur l'image --- */
    .store-img{ position:relative; }
    .store-status{
        position:absolute; top:10px; left:10px; z-index:2;
        border-radius:999px; padding:.35rem .6rem; font-weight:700; font-size:.8rem;
        box-shadow:0 4px 18px rgba(0,0,0,.28);
    }
    .store-status.open { background:#2ecc71; color:#0b1f10; }
    .store-status.closed { background:#2E4C97; color:#fff; }

    /* on neutralise la vieille badge au header si elle restait */
    .store-card .header-status{ display:none !important; }
</style>
<script>
    (() => {
        const bar = document.querySelector('.pi-marquee');
        if (!bar) return;

        let lastY = window.scrollY;
        const threshold = 80;   // à partir de 80px de scroll, on autorise le masquage
        const delta     = 6;    // ignore les micro-mouvements

        const onScroll = () => {
            const y = window.scrollY;

            // tout en haut => toujours visible
            if (y < threshold) {
                bar.classList.remove('is-hidden');
                lastY = y;
                return;
            }

            // si on descend franchement -> cacher ; si on remonte -> montrer
            if (Math.abs(y - lastY) > delta) {
                if (y > lastY) bar.classList.add('is-hidden');
                else           bar.classList.remove('is-hidden');
                lastY = y;
            }
        };

        // premier état correct puis écoute
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    })();
</script>
</body>
</html>