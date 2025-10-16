<?php
/* =========================
   Nos Magasins — Paristanbul
   ========================= */

define('DEV_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', DEV_MODE ? '1' : '0');

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

/* --- Connexion PDO (multi-DSN) --- */
$pdo = null;
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
    $credList = [['root',''],['root','root']];
    $lastErr = null;
    foreach ($dsnList as $dsn) {
        foreach ($credList as [$u,$p]) {
            try { $pdo = new PDO($dsn,$u,$p,$options); break 2; }
            catch(Throwable $e){ $lastErr=$e->getMessage(); }
        }
    }
    if(!$pdo){ throw new RuntimeException('DB connect failed: '.$lastErr); }
} catch(Throwable $e){
    if(DEV_MODE){
        echo '<div style="background:#2b2b2b;color:#fff;padding:10px;border:1px solid #444;margin:10px 0"><strong>Erreur BDD:</strong> '.htmlspecialchars($e->getMessage()).'</div>';
    }
}

/* --- coords par ville (approx) --- */
$coords = [
    'nogent-sur-oise' => [49.278948, 2.464688],
    'villemomble'     => [48.8890,   2.5040],
    'bondy'           => [48.9022,   2.48278],
    'drancy'          => [48.924298, 2.445676],
    'villiers-le-bel' => [49.0094,   2.3911],
    'vert-saint-denis'=> [48.5707,   2.6296],
];

/* helpers */
function slugify($s){ $s=iconv('UTF-8','ASCII//TRANSLIT',$s); $s=strtolower(preg_replace('~[^\pL\d]+~u','-',$s)); return trim($s,'-'); }
function utf8_clean_array(&$arr){ array_walk_recursive($arr,function (&$v){ if(is_string($v)){ $v=preg_replace('/[^\PC\s]/u','',$v); if(!mb_check_encoding($v,'UTF-8')){ $v=mb_convert_encoding($v,'UTF-8','UTF-8, ISO-8859-1, ISO-8859-15, Windows-1252'); }}}); }

/* build magasins */
$magasins=[];
if($pdo){
    try{
        $stmt=$pdo->query("SELECT * FROM magasins");
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            $villeRaw=trim((string)$row['ville_magasin']);
            $villeClean=preg_replace('~\s*\(.*?\)\s*~','',$villeRaw);
            $villeClean=preg_replace('~\d.*$~','',$villeClean);
            $villeClean=trim($villeClean);
            $villeKey=preg_replace('~-+~','-',slugify($villeClean));

            $match=null;
            foreach($coords as $k=>$_){ if($villeKey===$k || str_contains($villeKey,$k) || str_contains($k,$villeKey)){ $match=$k; break; } }
            if(!$match){
                $hay=slugify(($row['rue']??'').' '.($row['cp']??'').' '.($row['ville_magasin']??'').' '.($row['nom']??''));
                foreach($coords as $k=>$_){ if(str_contains($hay,$k)){ $match=$k; break; } }
            }
            [$lat,$lon]=$match?$coords[$match]:[48.8566,2.3522];

            $o=substr((string)$row['horaire_ouverture'],0,5);
            $f=substr((string)$row['horaire_fermeture'],0,5);

            $magasins[]=[
                "nom"=>"Paristanbul ".$villeClean,
                "ville"=>$villeClean,
                "cp"=>$row['cp']??'',
                "adresse"=>trim(($row['rue']??'').", ".($row['cp']??'')." ".$villeClean),
                "tel"=>$row['num_tel']??'',
                "horaires"=>($row['jours_ouverture']??'')." : ".$o."–".$f,
                "h_ouverture"=>$o,
                "h_fermeture"=>$f,
                "lat"=>(float)$lat, "lon"=>(float)$lon,
                "services"=>array_values(array_filter(["Boucherie","Épicerie", !empty($row['parking'])?"Parking":null, !empty($row['drive'])?"Drive":null ])),
                "image"=>$row['image']??''
            ];
        }
    }catch(Throwable $e){ /* silencieux */ }
}
utf8_clean_array($magasins);
$magasinsJson=json_encode($magasins,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PARTIAL_OUTPUT_ON_ERROR) ?: '[]';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nos Magasins - Paristanbul</title>

    <!-- Fonts + Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet + Cluster -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">

    <style>
        :root{
            --ink:#E6E9F2; --muted:#9aa3b2; --pi-red:#e21b3c; --pi-blue:#2E4C97;
            --hair:rgba(255,255,255,.10); --hair-strong:rgba(255,255,255,.18);
        }
        body{font-family:"Plus Jakarta Sans",system-ui,Segoe UI,Roboto,Arial;background:#0b1120;color:var(--ink);}

        /* ====== HEADER (pi-simple) ====== */
        header.pi-simple{ background:transparent; border-bottom:1px solid rgba(255,255,255,.06); }
        .pi-simple .topbar{ display:grid; grid-template-columns:1fr auto 1fr; align-items:center; gap:16px; padding:16px 0; }
        .pi-simple .social{ display:flex; gap:16px; color:#c9d4ea }
        .pi-simple .social a{ color:#c9d4ea; font-size:18px }
        .pi-simple .social a:hover{ color:#fff }
        .pi-simple .brand{ display:flex; flex-direction:column; align-items:center; gap:6px }
        .pi-simple .brand img{ height:60px; width:auto }
        .pi-simple .tagline{ display:flex; align-items:center; gap:12px; color:#c9d4ea; font-size:13px }
        .pi-simple .tagline .rule{ width:64px; height:1px; background:rgba(255,255,255,.12) }
        .pi-simple .right-col{ display:flex; justify-content:flex-end; align-items:center; gap:10px; font-weight:800 }
        .pi-simple .phone{ color:#e7ecf5; font-weight:800 }
        .pi-simple .divider{ border:0; border-top:1px solid rgba(255,255,255,.06); margin:0 }
        .pi-simple .navrow{ padding:12px 0 }
        .pi-simple .menu{ list-style:none; display:flex; justify-content:center; gap:28px; margin:0; padding:0 }
        .pi-simple .menu a{ font-weight:800; font-size:14px; color:#c9d4ea; letter-spacing:.06em; text-transform:uppercase }
        .pi-simple .menu a:hover,.pi-simple .menu a.is-active{ color:#fff }

        /* ===== Marquee en haut ===== */
        .pi-marquee{position:sticky;top:0;z-index:60;overflow:hidden;border-bottom:1px solid rgba(255,255,255,.08);background:transparent}
        .pi-marquee__inner{display:flex;gap:40px;padding:10px 0;white-space:nowrap;animation:piMarquee 22s linear infinite}
        .pi-pill{display:inline-flex;align-items:center;gap:8px;padding:6px 12px;border-radius:999px;background:linear-gradient(145deg,#101733,#111621);border:1px solid #1a2340;font-size:.92rem}
        .pi-pill .pi-dot{width:8px;height:8px;border-radius:50%;background:conic-gradient(from 90deg,#e21b3c,#2E4C97)}
        @keyframes piMarquee{from{transform:translateX(0)} to{transform:translateX(-50%)}}

        /* ===== Hero ===== */
        .hero{
            background:
                    radial-gradient(1200px 600px at 20% -10%, rgba(255,0,76,.25), transparent 60%),
                    radial-gradient(1400px 800px at 120% 10%, rgba(46,76,151,.25), transparent 60%),
                    linear-gradient(180deg,#0d1426 0%, #0b1120 100%);
            border-bottom:1px solid rgba(255,255,255,.06);
        }
        .hero .container{ padding:72px 0 }
        .hero h1{ font-weight:900 }
        .pi-word-anim{ --c1:#e21b3c; --c2:#2E4C97; background:linear-gradient(90deg,var(--c1),var(--c2),var(--c1)); background-size:200% 100%; -webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent; color:transparent; animation:word 6s ease-in-out infinite alternate }
        @keyframes word{ from{background-position:0% 50%} to{background-position:100% 50%} }
        .hero .lead{ color:#dfe6f5 }
        .btn-outline{ border:2px solid rgba(255,255,255,.55); color:#fff; padding:.55rem .9rem; border-radius:12px; background:transparent; font-weight:800 }
        .btn-outline.dark{ background:var(--pi-red); border-color:var(--pi-red) }

        /* KPI cartes blanches */
        .kpis .card{ background:#fff !important; color:#0b1120 !important; border:0 !important; border-radius:12px; box-shadow:0 8px 16px rgba(0,0,0,.12) }
        .kpi-number{ font-size:2rem; font-weight:800 }
        .kpis .text-muted{ color:#6b7280 !important }

        /* Locator / Map / Stores */
        #locator{ padding:22px }
        #map{ height:520px; border-radius:16px; border:1px solid var(--hair) }
        .leaflet-container{ background:#0F1524 }
        .leaflet-control-zoom a{ background:#151e31; color:#fff; border:1px solid var(--hair) }
        .leaflet-popup-content-wrapper,.leaflet-popup-tip{ background:#141b2b; color:#E6E9F2; border:none }

        .store-card{ background:transparent; border:1px solid var(--hair); border-radius:16px; transition:transform .18s ease, border-color .18s ease }
        .store-card:hover{ transform:translateY(-3px); border-color:var(--hair-strong) }
        .store-img{ height:164px; background:#0f1524; border-radius:12px; overflow:hidden; position:relative }
        .store-img img{ width:100%; height:100%; object-fit:cover }
        .store-status{ position:absolute; top:10px; left:10px; border-radius:999px; padding:.35rem .6rem; font-weight:800; font-size:.8rem; box-shadow:0 4px 18px rgba(0,0,0,.28) }
        .store-status.open{ background:#2ecc71; color:#0b1f10 }
        .store-status.closed{ background:#2E4C97; color:#fff }
        .badge-pill{ border-radius:999px; padding:.35rem .6rem; font-weight:700; border:1px solid var(--hair) }
        .badge-purple{ background:#7b5cff;color:#fff;border-color:transparent }
        .badge-blue{ background:#2E4C97;color:#fff;border-color:transparent }
        .badge-green{ background:#2ecc71;color:#0b1f10;border-color:transparent }
        .badge-amber{ background:#f1c40f;color:#2d2300;border-color:transparent }
        .input-group .input-group-text{ background:#121a2a; border-color:#121a2a; color:#cfe0ff }
        #searchInput.form-control,#sortSelect{ background:#121a2a; border-color:#121a2a; color:#e6e9f2 }

        /* ===== Footer ===== */
        footer.pi-footer{
            position:relative; isolation:isolate;
            background:
                    radial-gradient(900px 500px at 10% -10%, rgba(46,76,151,.12), transparent 60%),
                    radial-gradient(900px 500px at 90% -10%, rgba(214,69,46,.10), transparent 55%),
                    linear-gradient(180deg, #0f1525, #0c1223);
            border-top:1px solid #141a2b; box-shadow:inset 0 12px 40px rgba(0,0,0,.35)
        }
        .pi-footer .wrap{ max-width:1100px; margin:0 auto; text-align:center; padding:40px 20px }
        .pi-footer .brand{ height:72px; width:auto; display:block; margin:0 auto 18px }
        .pi-footer .headline{ display:flex; align-items:center; justify-content:center; gap:16px; margin:6px auto 18px }
        .pi-footer .headline h2{ margin:0; font-weight:900; letter-spacing:.14em; color:#D6452E; font-size:24px }
        .pi-footer .headline .line{ height:4px; width:260px; border-radius:2px; background:#D6452E }
        .pi-footer .social{ list-style:none; display:flex; justify-content:center; gap:14px; padding:0; margin:14px 0 20px }
        .pi-footer .social a{ width:42px; height:42px; display:grid; place-items:center; background:#101733; color:#cfe0ff; border-radius:50%; border:1px solid #1e2740; font-size:18px; transition:.2s }
        .pi-footer .social a:hover{ background:linear-gradient(145deg,#2E4C97,#D6452E); border-color:#2a3659; color:#fff; transform:translateY(-2px) }
        .pi-footer .footer-nav{ display:flex; flex-wrap:wrap; justify-content:center; gap:26px 30px; padding:12px 0 8px; margin:0 auto 12px }
        .pi-footer .footer-nav a{ color:#e9f1ff; font-weight:800; font-size:14px; letter-spacing:.06em; text-transform:uppercase }
        .pi-footer .footer-nav a:hover{ color:#D6452E }
        .pi-footer .copyright{ font-size:12px; color:#c9d4ea }

        /* Centrage strict de la headline */
        .pi-footer .headline{ width:fit-content; margin-left:auto; margin-right:auto }
        .pi-footer .headline .line{ flex:1 1 auto }
        @media (max-width:720px){
            .pi-simple .topbar{ grid-template-columns:1fr; text-align:center }
            .pi-simple .right-col{ justify-content:center }
            .pi-footer .headline .line{ width:20vw }
            .pi-footer .headline h2{ font-size:20px }
        }
    </style>
</head>
<body>

<!-- ===== MARQUEE ===== -->
<div class="pi-marquee" aria-hidden="true">
    <div class="pi-marquee__inner">
        <span class="pi-pill"><span class="pi-dot"></span> Bondy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Drancy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villemomble</span>
        <span class="pi-pill"><span class="pi-dot"></span> Vert-Saint-Denis</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villiers-le-Bel</span>
        <span class="pi-pill"><span class="pi-dot"></span> Nogent-sur-Oise</span>
        <span class="pi-pill"><span class="pi-dot"></span> Bondy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Drancy</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villemomble</span>
        <span class="pi-pill"><span class="pi-dot"></span> Vert-Saint-Denis</span>
        <span class="pi-pill"><span class="pi-dot"></span> Villiers-le-Bel</span>
        <span class="pi-pill"><span class="pi-dot"></span> Nogent-sur-Oise</span>
    </div>
</div>

<!-- ===== HEADER (pi-simple) ===== -->
<header class="pi-simple">
    <div class="container topbar">
        <div class="left-col">
            <nav class="social" aria-label="Réseaux sociaux">
                <a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@supermarche_paristanbul" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                <a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </nav>
        </div>

        <div class="brand">
            <a href="index.php" class="navbar-brand">
                <img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul">
            </a>
            <div class="tagline">
                <span class="rule"></span><span>Since 1993</span><span class="rule"></span>
            </div>
        </div>

        <div class="right-col">
            <i class="fa-solid fa-phone"></i>
            <a class="phone" href="tel:+33749826133">07 49 82 61 33</a>
        </div>
    </div>

    <hr class="divider">

    <div class="container navrow">
        <ul class="menu" aria-label="Navigation principale">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre Histoire</a></li>
            <li><a href="#locator">Carte</a></li>
            <li><a href="nosMagasins.php" class="is-active">Nos Magasins</a></li>
            <li><a href="postuler.php">Postuler</a></li>
            <li><a href="index.php#contact">Contact</a></li>
        </ul>
    </div>

    <hr class="divider">
</header>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="display-5">Trouvez votre <span class="pi-word-anim">Paristanbul</span> le plus proche</h1>
                <p class="lead mb-3">Boucheries &amp; épiceries de qualité, partout près de vous.</p>
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

<!-- ===== LOCATOR ===== -->
<main class="container my-4" id="locator">
    <div class="row g-3">
        <div class="col-lg-5 order-lg-2">
            <div class="sticky-map">
                <div class="d-flex gap-2 align-items-center mb-2">
                    <div class="input-group">
                        <span class="input-group-text border-end-0"><i class="bi bi-search text-muted"></i></span>
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
                <?php for($i=0;$i<6;$i++): ?>
                    <div class="col-md-6"><div class="store-card p-3"><div class="store-img"></div></div></div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</main>

<!-- ===== FOOTER ===== -->
<footer class="pi-footer">
    <div class="wrap">
        <a href="index.php"><img class="brand" src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul"></a>
        <div class="headline" id="footerHeadline">
            <span class="line" aria-hidden="true"></span>
            <h2>REJOIGNEZ-NOUS</h2>
            <span class="line" aria-hidden="true"></span>
        </div>
        <ul class="social" aria-label="Réseaux sociaux" id="footerSocial">
            <li><a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a></li>
            <li><a href="https://www.instagram.com/paristanbul_supermarche/?hl=fr" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a></li>
            <li><a href="https://www.tiktok.com/@supermarche_paristanbul" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a></li>
            <li><a href="https://www.youtube.com/channel/UCsjy3bdpFzBwM7MF923gKvA" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a></li>
        </ul>
        <nav class="footer-nav" aria-label="Navigation pied de page">
            <a href="index.php">Accueil</a>
            <a href="nosMagasins.php">Nos magasins</a>
            <a href="#locator">Carte</a>
            <a href="quiSommesNous.html">À propos</a>
            <a href="postuler.php">Postuler</a>
            <a href="index.php#contact">Contact</a>
        </nav>
        <p class="copyright">© <span id="year"></span> Paristanbul — Tous droits réservés.</p>
    </div>
</footer>

<!-- ===== JS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script>
    /* PHP -> JS */
    const magasins = (()=>{ try{ return <?php echo $magasinsJson; ?> }catch(_){ return [] } })();
    const FALLBACK = [
        { nom:"Paristanbul Villiers-le-Bel", ville:"Villiers-le-Bel", cp:"95400", adresse:"117 Avenue Pierre Semard, 95400 Villiers-le-Bel", tel:"+33 7 49 82 61 33", h_ouverture:"08:30", h_fermeture:"20:00", horaires:"Lun-Dim : 08:30–20:00", lat:49.0094, lon:2.3911, services:["Boucherie","Épicerie","Parking"], image:"../assets/img/magasins/vlb.jpg" },
        { nom:"Paristanbul Bondy", ville:"Bondy", cp:"93140", adresse:"116 Avenue Gallieni, 93140 Bondy", tel:"+33 7 49 82 61 33", h_ouverture:"08:30", h_fermeture:"20:00", horaires:"Lun-Dim : 08:30–20:00", lat:48.9022, lon:2.48278, services:["Boucherie","Épicerie"], image:"../assets/img/magasins/bondy.jpg" },
        { nom:"Paristanbul Drancy", ville:"Drancy", cp:"93700", adresse:"83 Avenue Marceau, 93700 Drancy", tel:"+33 7 49 82 61 33", h_ouverture:"08:30", h_fermeture:"20:30", horaires:"Lun-Dim : 08:30–20:30", lat:48.924298, lon:2.445676, services:["Boucherie","Épicerie","Drive"], image:"../assets/img/magasins/drancy.jpg" }
    ];
    const LIST_ORIG = (Array.isArray(magasins) && magasins.length)? magasins : FALLBACK;

    /* Utils */
    const toMinutes = hhmm => { const [h,m]=String(hhmm||"").split(":").map(n=>parseInt(n||0,10)); return h*60+(m||0); };
    const nowMins = () => { const d=new Date(); return d.getHours()*60+d.getMinutes(); };
    const isOpenNow = m => { const o=toMinutes(m.h_ouverture), f=toMinutes(m.h_fermeture), n=nowMins(); return (o<f)?(n>=o&&n<=f):(n>=o||n<=f); };
    const km=(a,b,c,d)=>{ const R=6371, rad=x=>x*Math.PI/180, dLat=rad(c-a), dLon=rad(d-b); const A=Math.sin(dLat/2)**2+Math.cos(rad(a))*Math.cos(rad(c))*Math.sin(dLon/2)**2; return R*2*Math.atan2(Math.sqrt(A),Math.sqrt(1-A)); };
    const formatKm=v=>(v<1?(v*1000|0)+' m':v.toFixed(1)+' km');
    const itineraireURL=(m,o)=>{ const dest=`${m.lat},${m.lon}`; let u=`https://www.google.com/maps/dir/?api=1&destination=${dest}`; if(o) u+=`&origin=${o.lat},${o.lon}`; return u; };
    const telLink=t=>`tel:${(t||'').replace(/\s+/g,'')}`;

    /* State */
    let userPos=null;
    let LIST=LIST_ORIG.map((m,i)=>({...m,id:i}));

    /* KPI */
    function setKPIs(list){
        document.getElementById('kpiStores').textContent=list.length;
        const deps=new Set(list.map(m=>String(m.cp||'').slice(0,2)).filter(Boolean));
        document.getElementById('kpiDepartments').textContent=deps.size||"—";
        document.getElementById('kpiOpenNow').textContent=list.filter(isOpenNow).length;
    }

    /* Map */
    let map, clusterLayer, markersById={};
    function initMap(){
        map=L.map('map',{zoomControl:true}).setView([46.6031,1.8883],6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);
        clusterLayer=L.markerClusterGroup({showCoverageOnHover:false,maxClusterRadius:48});
        map.addLayer(clusterLayer);
        renderMarkers(LIST);
        setTimeout(()=>map.invalidateSize(),150);
    }
    function markerIcon(open){
        const color=open?'#e21b3c':'#2E4C97';
        const svg=encodeURIComponent(`<svg xmlns='http://www.w3.org/2000/svg' width='44' height='44' viewBox='0 0 44 44'><path fill='${color}' d='M22 2c8.8 0 16 7.2 16 16 0 11-16 24-16 24S6 29 6 18C6 9.2 13.2 2 22 2z'/><circle cx='22' cy='18' r='6' fill='white'/></svg>`);
        return L.icon({iconUrl:`data:image/svg+xml,${svg}`, iconSize:[44,44], iconAnchor:[22,40], popupAnchor:[0,-36]});
    }
    function renderMarkers(list){
        clusterLayer.clearLayers(); markersById={};
        list.forEach(m=>{
            const mk=L.marker([m.lat,m.lon],{icon:markerIcon(isOpenNow(m))}).bindPopup(`<strong>${m.nom}</strong><br>${m.adresse}<br><small>${m.horaires}</small>`);
            mk.on('click',()=>{ const el=document.querySelector(`[data-store="${m.id}"]`); if(el){ el.scrollIntoView({behavior:'smooth',block:'center'}); el.classList.add('ring'); setTimeout(()=>el.classList.remove('ring'),800);} });
            clusterLayer.addLayer(mk); markersById[m.id]=mk;
        });
    }

    /* List render */
    function serviceBadge(s){
        let cls='badge-pill bg-secondary';
        if(s==='Boucherie')cls='badge-pill badge-purple';
        else if(s==='Épicerie')cls='badge-pill badge-blue';
        else if(s==='Drive')cls='badge-pill badge-green';
        else if(s==='Parking')cls='badge-pill badge-amber';
        return `<span class="${cls} me-1 mb-1 d-inline-block">${s}</span>`;
    }
    function cardHTML(m,dist){
        const open=isOpenNow(m);
        const distance=dist!=null?`<span class="text-muted"><i class="bi bi-person-walking"></i> ${formatKm(dist)}</span>`:'';
        const tel=m.tel?`<a class="btn btn-outline-light btn-sm" href="${telLink(m.tel)}"><i class="bi bi-telephone"></i> Appeler</a>`:'';
        return `
<div class="col-md-6">
  <article class="store-card p-3" tabindex="0" data-store="${m.id}">
    <div class="store-img mb-3">
      <span class="store-status ${open?'open':'closed'}">${open?'Ouvert':'Fermé'}</span>
      ${m.image?`<img src="${m.image}" loading="lazy" alt="${m.nom}">`:`<div style="width:100%;height:100%"></div>`}
    </div>
    <div class="d-flex align-items-start justify-content-between mb-1"><h5 class="mb-0">${m.nom}</h5></div>
    <div class="small text-muted mb-2"><i class="bi bi-geo-alt-fill text-danger"></i> ${m.adresse}</div>
    <div class="small mb-2"><i class="bi bi-clock text-danger"></i> ${m.horaires}</div>
    <div class="mb-2">${(m.services||[]).map(serviceBadge).join(' ')}</div>
    <div class="d-flex flex-wrap gap-2">
      <a class="btn btn-danger btn-sm" href="${itineraireURL(m,userPos)}" target="_blank" rel="noopener"><i class="bi bi-geo-fill"></i> Itinéraire</a>
      ${tel}
      <button class="btn btn-outline-light btn-sm" data-copy="${m.adresse}"><i class="bi bi-clipboard"></i> Copier l’adresse</button>
      <button class="btn btn-outline-light btn-sm" data-share='${JSON.stringify({title:m.nom,text:m.adresse,url:itineraireURL(m,userPos)})}'><i class="bi bi-share"></i> Partager</button>
      <span class="ms-auto small">${distance}</span>
    </div>
  </article>
</div>`;
    }
    function renderList(list){
        const wrap=document.getElementById('list');
        wrap.innerHTML=list.map(m=>{ let d=null; if(userPos) d=km(userPos.lat,userPos.lon,m.lat,m.lon); return cardHTML(m,d); }).join('');
        wrap.querySelectorAll('[data-store]').forEach(el=>{
            el.addEventListener('click',()=>{ const id=el.getAttribute('data-store'), m=LIST.find(x=>x.id==id); if(m && markersById[id]){ map.flyTo([m.lat,m.lon],14,{duration:.6}); markersById[id].openPopup(); } });
        });
        wrap.querySelectorAll('[data-copy]').forEach(btn=>{
            btn.addEventListener('click',async()=>{ try{ await navigator.clipboard.writeText(btn.getAttribute('data-copy')); btn.innerHTML='<i class="bi bi-clipboard-check"></i> Copié !'; setTimeout(()=>btn.innerHTML='<i class="bi bi-clipboard"></i> Copier l’adresse',1400); }catch{} });
        });
        wrap.querySelectorAll('[data-share]').forEach(btn=>{
            btn.addEventListener('click',async()=>{ try{ const payload=JSON.parse(btn.getAttribute('data-share')); if(navigator.share) await navigator.share(payload); else window.open(payload.url,'_blank','noopener'); }catch{} });
        });
    }

    /* Search / filter / sort */
    let activeFilter='all';
    function applySearchFilterSort(){
        const q=(document.getElementById('searchInput').value||'').toLowerCase().trim();
        let out=LIST_ORIG.filter(m=>{
            const hay=[m.nom,m.ville,m.cp,m.adresse].join(' ').toLowerCase();
            const hit=!q||hay.includes(q);
            const fit=(activeFilter==='all')||(m.services||[]).includes(activeFilter);
            return hit&&fit;
        });
        const sort=document.getElementById('sortSelect').value;
        if(sort==='name') out.sort((a,b)=>a.nom.localeCompare(b.nom));
        else if(sort==='open') out.sort((a,b)=>(isOpenNow(b)-isOpenNow(a))||a.nom.localeCompare(b.nom));
        else if(sort==='distance' && userPos) out.sort((a,b)=>km(userPos.lat,userPos.lon,a.lat,a.lon)-km(userPos.lat,userPos.lon,b.lat,b.lon));

        LIST=out.map((m,i)=>({...m,id:m.id??i}));
        renderMarkers(LIST);
        renderList(LIST);
        setKPIs(LIST);
    }

    /* Geolocate */
    document.getElementById('locateMe').addEventListener('click', ()=>{
        if(!navigator.geolocation){ alert("Votre navigateur ne supporte pas la géolocalisation."); return; }
        navigator.geolocation.getCurrentPosition(pos=>{
            userPos={lat:pos.coords.latitude, lon:pos.coords.longitude};
            map.setView([userPos.lat,userPos.lon],11);
            L.marker([userPos.lat,userPos.lon]).addTo(map).bindPopup("📍 Vous êtes ici").openPopup();
            document.getElementById('sortSelect').value='distance';
            applySearchFilterSort();
        },()=>alert("Impossible de vous localiser."),{enableHighAccuracy:true,timeout:8000,maximumAge:0});
    });

    /* Listeners */
    document.getElementById('searchInput').addEventListener('input', applySearchFilterSort);
    document.querySelectorAll('[data-filter]').forEach(btn=>{
        btn.addEventListener('click',()=>{
            activeFilter=btn.getAttribute('data-filter');
            document.querySelectorAll('[data-filter]').forEach(b=>b.classList.remove('btn-light'));
            btn.classList.add('btn-light');
            applySearchFilterSort();
        });
    });
    document.getElementById('sortSelect').addEventListener('change', applySearchFilterSort);

    /* Boot */
    document.addEventListener('DOMContentLoaded', ()=>{
        document.getElementById('year').textContent=new Date().getFullYear();
        initMap();
        setKPIs(LIST_ORIG);
        applySearchFilterSort();
    });

    /* Footer headline = largeur des icônes pour un centrage parfait */
    (function(){
        const social=document.getElementById('footerSocial');
        const headline=document.getElementById('footerHeadline');
        if(!social||!headline) return;
        function fit(){ const w=Math.round(social.getBoundingClientRect().width); if(w) headline.style.width=w+'px'; }
        window.addEventListener('load',fit,{once:true}); window.addEventListener('resize',()=>requestAnimationFrame(fit));
    })();
</script>
</body>
</html>
