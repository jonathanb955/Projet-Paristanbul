<?php
/* =========================
   Nos Magasins — Paristanbul (upgrade)
   ========================= */

// ====== MODE DEV (à adapter) ======
define('DEV_MODE', true); // true pour voir les erreurs BDD à l'écran
// Pour désactiver temporairement le fallback JS pendant le debug, passe DISABLE_FALLBACK_JS à true
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
        'vert-saint-denis'  => [48.5707,   2.6296],
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

            // 1) Nettoyage (retire parenthèses / chiffres parasites)
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
        /* Forcer couleur claire sur la nav */
        .navbar .nav-link {
            color: #e6e9f2 !important;   /* gris clair */
            font-weight: 600;
            opacity: .9;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus {
            color: #fff !important;      /* blanc au survol */
            opacity: 1;
        }

        /* Si tu veux l'onglet actif plus visible */
        .navbar .nav-link.active {
            color: #e21b3c !important;   /* rouge Paristanbul */
            font-weight: 700;
        }
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
            background: radial-gradient(1200px 600px at 20% -10%, rgba(255,0,76,.25), transparent 60%),
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
</head>
<body class="pi-theme">

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
                <!-- Liens centraux -->
                <ul class="navbar-nav mx-lg-auto my-3 my-lg-0 gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="postuler.php">Postuler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="quiSommesNous.html">Notre histoire</a>
                    </li>
                </ul>

                <!-- Boutons à droite -->
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
                <h1 class="display-5">Trouvez votre <span style="color:#e21b3c">Paristanbul</span> le plus proche</h1>
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
      <div class="store-img mb-3">${m.image ? `<img src="${m.image}" loading="lazy" alt="${m.nom}">` : `<div class="skeleton"></div>`}</div>
      <div class="d-flex align-items-start justify-content-between mb-1">
        <h5 class="mb-0">${m.nom}</h5>
        <span class="badge ${open?'bg-success':'bg-secondary'}">${open?'Ouvert':'Fermé'}</span>
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
            btn.addEventListener('click', async e=>{
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
        // console.log('magasins depuis PHP:', magasins); // utile pour debug
    });
</script>
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
</style>
</body>
</html>