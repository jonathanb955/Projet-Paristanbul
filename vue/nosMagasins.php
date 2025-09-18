<?php
/* =========================
   Données magasins (MySQL)
   ========================= */
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (Throwable $e) {
    error_log('DB error: '.$e->getMessage());
    $pdo = null;
}

// dictionnaire GPS avec clés normalisées (slug, sans accents)
$coords = [
    'nogent-sur-oise'  => [49.278948, 2.464688],
    'villemomble'      => [48.8890,   2.5040],
    'bondy'            => [48.9022,   2.48278],
    'drancy'           => [48.924298, 2.445676],
    'villiers-le-bel'  => [49.0094,   2.3911],
];

$magasins = [];
if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM magasins");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ville = trim((string)$row['ville_magasin']);
            // normalise la ville pour matcher les clés de $coords
            $villeKey = iconv('UTF-8','ASCII//TRANSLIT',$ville);
            $villeKey = strtolower(preg_replace('~[^\pL\d]+~u','-',$villeKey));
            $villeKey = trim($villeKey,'-');

            // récupère lat/lon (fallback Paris si inconnu)
            [$lat, $lon] = $coords[$villeKey] ?? [48.85, 2.35];

            $magasins[] = [
                "nom"      => "Paristanbul " . $ville,
                "adresse"  => trim(($row['rue'] ?? '') . ", " . ($row['cp'] ?? '') . " " . $ville),
                "tel"      => $row['num_tel'] ?? '',
                "horaires" => ($row['jours_ouverture'] ?? '') . " : " .
                    substr((string)$row['horaire_ouverture'], 0, 5) . "–" .
                    substr((string)$row['horaire_fermeture'], 0, 5),
                "lat"      => (float)$lat,
                "lon"      => (float)$lon,
                "services" => ["Boucherie","Épicerie"],
                "image"    => $row['image'] ?? ''
            ];
        }
    } catch (Throwable $e) {
        error_log('SQL error: '.$e->getMessage());
    }
}

$magasinsJson = json_encode($magasins, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if ($magasinsJson === false) { $magasinsJson = '[]'; }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nos Magasins - Paristanbul</title>

    <link rel="stylesheet" href="../assets/css/index.css" />
    <link rel="stylesheet" href="../assets/css/nosMagasins.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        #map { height: 460px; border-radius: 16px; border:1px solid #2a3654; }
        .leaflet-container { background:#0F1524; }
        .leaflet-control-zoom a { background:#151e31; color:#fff; border:none; box-shadow:0 4px 14px rgba(0,0,0,.25) }
        .leaflet-bar a:hover { background:#1b2436 }
        .leaflet-popup-content-wrapper,.leaflet-popup-tip{ background:#141b2b; color:#E6E9F2; border:none }

        .image-wrapper{ height:180px; display:flex; align-items:center; justify-content:center; background:#0F1524; }
        .image-wrapper img{ width:100%; height:100%; object-fit:cover; border-radius:12px; }
        .magasin-card{ background:var(--card)!important; color:var(--ink)!important; border:1px solid #1e2a44; border-radius:16px; }
        .magasin-card .text-muted{ color:var(--muted)!important; }
        .badge.bg-purple{ background:#7b5cff!important; color:#fff!important; }
        .badge.bg-primary{ background:#2E4C97!important; }
        .btn-outline { border:2px solid rgba(255,255,255,.75); color:var(--ink); padding:.6rem 1rem; border-radius:10px; text-decoration:none; font-weight:700; background:transparent; }
        .btn-outline.dark{ background:var(--pi-red); border-color:var(--pi-red); color:#fff; }
    </style>
</head>

<body class="pi-theme">
<header>
    <nav class="navbar">
        <div class="logo">
            <a href="index.php"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" style="width:300px" alt="Paristanbul"></a>
        </div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre histoire</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="pageInscription.php" class="btn-light">Inscription</a>
            <a href="pageConnexion.php" class="btn-dark">Connexion</a>
        </div>
    </nav>
</header>

<section class="hero-section">
    <div class="hero-content">
        <div class="hero-text">
            <h1>Nos magasins</h1>
            <p>Retrouvez tous nos points de vente près de chez vous</p>
            <div class="hero-buttons">
                <button id="locateMe" class="btn-outline dark"><i class="bi bi-crosshair"></i> Me localiser</button>
            </div>
        </div>
    </div>
</section>

<div class="container my-4 reveal">
    <div class="row align-items-center justify-content-center">
        <div class="col-md-6 mb-2 mb-md-0">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0" placeholder="Rechercher par ville ou code postal...">
            </div>
        </div>
        <div class="col-auto"><button class="btn-outline"><i class="bi bi-funnel"></i> Filtrer</button></div>
        <div class="col-auto"><button class="btn-green">Rechercher</button></div>
    </div>
</div>

<div class="container my-4">
    <div id="map" class="reveal"></div>
</div>

<div class="container my-5">
    <h2 class="section-title">Nos magasins en France</h2>
    <p class="section-subtitle">Trouvez le Paristanbul le plus proche de chez vous.</p>
    <div class="row" id="magasin-list"></div>
</div>

<script>
    /* ===== Données PHP -> JS (béton armé) ===== */
    const magasins = (() => {
        try { return <?php echo ($magasinsJson ?: '[]'); ?>; }
        catch(e){ console.warn('Parse magasins JSON failed:', e); return []; }
    })();

    // Fallback si la BDD est vide
    const FALLBACK_MAGASINS = [
        { nom:"Paristanbul Villiers-le-Bel", adresse:"117 Avenue Pierre Semard, 95400 Villiers-le-Bel", tel:"+33 7 49 82 61 33", horaires:"Lun-Dim : 8h30–20h",   lat:49.0094,   lon:2.3911,   services:["Boucherie","Épicerie"], image:"../assets/img/magasins/vlb.jpg" },
        { nom:"Paristanbul Bondy",          adresse:"116 Avenue Gallieni, 93140 Bondy",                 tel:"+33 7 49 82 61 33", horaires:"Lun-Dim : 8h30–20h",   lat:48.9022,   lon:2.48278,  services:["Boucherie","Épicerie"], image:"../assets/img/magasins/bondy.jpg" },
        { nom:"Paristanbul Drancy",         adresse:"83 Avenue Marceau, 93700 Drancy",                  tel:"+33 7 49 82 61 33", horaires:"Lun-Dim : 8h30–20h30", lat:48.924298, lon:2.445676, services:["Boucherie","Épicerie"], image:"../assets/img/magasins/drancy.jpg" }
    ];

    // ✅ Toujours une liste exploitable (UNE SEULE déclaration de LIST)
    const LIST = (Array.isArray(magasins) && magasins.length > 0) ? magasins : FALLBACK_MAGASINS;
    console.log("Magasins chargés :", LIST.length, LIST);

    /* ===== Helpers ===== */
    function km(lat1, lon1, lat2, lon2) {
        const R = 6371, toRad = d => d*Math.PI/180;
        const dLat = toRad(lat2-lat1), dLon = toRad(lon2-lon1);
        const a = Math.sin(dLat/2)**2 + Math.cos(toRad(lat1))*Math.cos(toRad(lat2))*Math.sin(dLon/2)**2;
        return (R*2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a))).toFixed(1);
    }
    function itineraireURL(destLat, destLon, destAddr, origin) {
        const dest = (destLat && destLon) ? `${destLat},${destLon}` : encodeURIComponent(destAddr||'');
        let url = `https://www.google.com/maps/dir/?api=1&destination=${dest}`;
        if (origin) url += `&origin=${origin.lat},${origin.lon}`;
        return url;
    }

    /* ===== Rendu cartes magasin ===== */
    function renderCards(user=null){
        const container = document.getElementById('magasin-list');
        container.innerHTML = '';
        LIST.forEach(m => {
            const distance = user ? `${km(user.lat,user.lon,m.lat,m.lon)} km` : '—';
            const servicesHtml = (m.services||[]).map(s=>{
                let cls='bg-secondary text-white';
                if(s==='Boucherie') cls='bg-purple';
                if(s==='Poissonnerie'||s==='Bio') cls='bg-warning text-dark';
                if(s==='Parking') cls='bg-primary';
                if(s==='Drive') cls='bg-success';
                return `<span class="badge ${cls} me-1">${s}</span>`;
            }).join('');
            const itURL = itineraireURL(m.lat,m.lon,m.adresse,user);

            // 🔧 Correction: on enlève 'reveal' du wrapper col-md-4
            container.insertAdjacentHTML('beforeend', `
        <div class="col-md-4 mb-4">
          <div class="magasin-card p-0 shadow-sm">
            <div class="p-4 bg-opacity-10 text-center position-relative" style="background:#141b2b;border-bottom:1px solid #1e2a44;">
              <div class="image-wrapper mb-3">
                <img src="${m.image||''}" class="img-fluid" alt="Image magasin" onerror="this.style.display='none'">
              </div>
              <span class="badge bg-success position-absolute top-0 end-0 m-2">Ouvert</span>
            </div>
            <div class="p-3">
              <h5 class="fw-bold">${m.nom}</h5>
              <p class="mb-1 text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> ${m.adresse}</p>
              <p class="mb-1"><i class="bi bi-clock text-danger"></i> ${m.horaires}</p>
              <p class="mb-1"><i class="bi bi-telephone text-danger"></i> ${m.tel}</p>
              <p class="mb-3"><i class="bi bi-person-walking text-danger"></i> ${distance}</p>
              <div class="mb-3">${servicesHtml}</div>
              <div class="d-flex gap-2">
                <a class="btn btn-danger w-50" href="${itURL}" target="_blank" rel="noopener"><i class="bi bi-geo-fill"></i> Itinéraire</a>
                <button class="btn btn-outline-dark w-50"><i class="bi bi-eye"></i> Détails</button>
              </div>
            </div>
          </div>
        </div>
      `);
        });
    }

    /* ===== Leaflet ===== */
    let map, userPos=null;
    document.addEventListener('DOMContentLoaded', () => {
        map = L.map('map', { zoomControl:true }).setView([46.6031, 1.8883], 6);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'© OpenStreetMap' }).addTo(map);

        LIST.forEach(m => L.marker([m.lat, m.lon]).addTo(map).bindPopup(`<strong>${m.nom}</strong><br>${m.adresse}`));

        setTimeout(()=> map.invalidateSize(), 120);
        renderCards();

        document.getElementById('locateMe').addEventListener('click', () => {
            if (!navigator.geolocation) { alert("Votre navigateur ne supporte pas la géolocalisation."); return; }
            navigator.geolocation.getCurrentPosition(
                pos => {
                    userPos = {lat: pos.coords.latitude, lon: pos.coords.longitude};
                    map.setView([userPos.lat, userPos.lon], 11);
                    L.marker([userPos.lat, userPos.lon]).addTo(map).bindPopup("📍 Vous êtes ici").openPopup();
                    renderCards(userPos);
                },
                () => alert("Impossible de vous localiser."),
                { enableHighAccuracy:true, timeout:7000, maximumAge:0 }
            );
        });
    });
</script>

<script>
    // Mini reveal & nav shadow (comme l’index)
    document.addEventListener('DOMContentLoaded', () => {
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!prefersReduced && 'IntersectionObserver' in window) {
            const sels = ['.hero-text','.magasin-card','#map'];
            const els  = sels.flatMap(s => Array.from(document.querySelectorAll(s)));
            els.forEach((el,i)=>{ el.classList.add('reveal'); el.style.setProperty('--reveal-delay', ((i%6)*60)+'ms'); });
            const io = new IntersectionObserver((entries)=>{
                entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('reveal--in'); io.unobserve(e.target);} });
            }, {threshold:.12, rootMargin:'0px 0px -10% 0px'});
            els.forEach(el=>io.observe(el));
        }
        const nav = document.querySelector('nav.navbar');
        window.addEventListener('scroll', () => {
            if (!nav) return;
            nav.classList.toggle('nav--scrolled', (window.scrollY||0) > 8);
            nav.classList.remove('nav--hide');
        }, {passive:true});
    });
</script>
</body>
</html>
