<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nos Magasins – Paristanbul</title>

    <!-- Design system (aligné sur index): couleurs, rayons, ombres, espaces -->
    <style>
        :root{
            --p-red:#d43b3b;           /* rouge Paristanbul */
            --p-navy:#003366;          /* bleu index */
            --p-navy-700:#0b4c8c;      /* hover liens */
            --p-cream:#fff9f9;         /* fond doux */
            --p-bg:#f7f7fb;            /* section light */
            --p-dark:#1b1f2a;
            --radius:16px;             /* arrondi global */
            --shadow:0 10px 30px rgba(0,0,0,.08);
            --shadow-sm:0 6px 18px rgba(0,0,0,.06);
            --gap: clamp(12px, 2vw, 20px);
        }
        *{box-sizing:border-box}
        html,body{margin:0;padding:0}
        body{font-family:system-ui,-apple-system,"Segoe UI",Roboto,Ubuntu,"Helvetica Neue",Arial; color:var(--p-dark); background:var(--p-bg)}

        /* NAVBAR (reprise visuelle de l'index) */
        .navbar{position:sticky;top:0;z-index:50;display:flex;align-items:center;justify-content:space-between;padding:14px clamp(10px,2.2vw,28px); background:#fff; box-shadow:0 2px 8px rgba(0,0,0,.04)}
        .navbar .logo img{height:54px; width:auto; display:block}
        .nav-links{display:flex;gap:22px; list-style:none; margin:0; padding:0}
        .nav-links a{color:#333; text-decoration:none; font-weight:600}
        .nav-links a.active, .nav-links a:hover{color:var(--p-navy-700)}
        .nav-actions{display:flex; gap:10px}
        .btn{border:0; border-radius:10px; padding:10px 14px; font-weight:700; cursor:pointer}
        .btn-light{background:#e6f3ff; color:var(--p-navy)}
        .btn-dark{background:var(--p-navy); color:#fff}

        /* HERO bandeau (dégradé index) */
        .hero{position:relative; overflow:hidden; padding: clamp(40px, 7vw, 80px) 16px; text-align:center; color:#fff; background: radial-gradient(1200px 400px at 10% -10%, rgba(255,255,255,.08) 0 60%, rgba(255,255,255,0) 60%),
        radial-gradient(800px 320px at 90% -20%, rgba(255,255,255,.08) 0 60%, rgba(255,255,255,0) 60%),
        linear-gradient(135deg, var(--p-navy) 0%, #ffffff 60%, var(--p-red) 120%)}
        .hero h1{margin:0 0 6px; font-size: clamp(28px, 5vw, 48px); text-shadow:0 2px 8px rgba(0,0,0,.25)}
        .hero p{margin:0 0 18px; font-size: clamp(16px, 2.6vw, 18px); opacity:.95}
        .hero .cta{display:flex; gap:10px; justify-content:center; flex-wrap:wrap}

        /* CONTENEUR */
        .container{max-width:1200px; margin:0 auto; padding:0 16px}

        /* Barre d’outils recherche/filtre */
        .toolbar{display:grid; grid-template-columns:1fr auto auto; gap:10px; align-items:center; margin-top: -22px; padding: 14px; background:#fff; border-radius:var(--radius); box-shadow:var(--shadow); transform:translateY(-22px)}
        .input{display:flex; align-items:center; gap:10px; background:#fff; border:1px solid #e9e9ef; border-radius:12px; padding:10px 12px}
        .input input{border:0; outline:0; width:100%; font-size:15px}
        .chip{border-radius:999px; padding:8px 12px; background:#fff; border:1px solid #e9e9ef; font-weight:600}

        /* Grille contenu: carte + liste */
        .layout{display:grid; grid-template-columns: 1.25fr .95fr; gap: var(--gap); align-items:start}
        @media (max-width: 1000px){.layout{grid-template-columns:1fr}}

        /* Carte Leaflet */
        #map{height:520px; width:100%; border-radius: var(--radius); box-shadow: var(--shadow); background:#fff}

        /* Liste magasins */
        .cards{display:grid; grid-template-columns:1fr; gap:var(--gap)}
        .card{background:#fff; border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow-sm); border:1px solid #eee}
        .card-head{position:relative; padding:14px; background: linear-gradient(180deg, rgba(212,59,59,.06), rgba(212,59,59,.02));}
        .badge-open{position:absolute; top:12px; right:12px; background:#24b26b; color:#fff; font-weight:800; padding:6px 10px; border-radius:10px; font-size:12px}
        .image-wrap{height:180px; display:flex; align-items:center; justify-content:center; overflow:hidden; border-radius:12px; background:#fff}
        .image-wrap img{width:100%; height:100%; object-fit:cover}
        .card-body{padding:14px 16px 16px}
        .title{margin:0 0 6px; font-size:18px}
        .meta{margin:6px 0; color:#555; display:grid; gap:6px}
        .meta i{color:var(--p-red); width:18px; display:inline-block; text-align:center}
        .services{display:flex; flex-wrap:wrap; gap:8px; margin:10px 0 14px}
        .badge{padding:6px 10px; font-size:12px; font-weight:700; border-radius:999px}
        .b-red{background:#ffe8e8; color:#9e1f1f}
        .b-green{background:#e8fff3; color:#126b3a}
        .b-blue{background:#e6f1ff; color:#0d3e87}
        .b-gold{background:#fff5da; color:#9a6a00}

        .actions{display:flex; gap:10px}
        .actions .btn{flex:1}

        /* Section services & newsletter: align index */
        .section{padding: clamp(28px, 6vw, 64px) 0}
        .section h3{margin:0 0 18px; font-size: clamp(20px, 3.2vw, 28px)}
        .grid-4{display:grid; grid-template-columns:repeat(4,1fr); gap:var(--gap)}
        @media (max-width: 900px){.grid-4{grid-template-columns:repeat(2,1fr)}}
        @media (max-width: 520px){.grid-4{grid-template-columns:1fr}}
        .service-card{background:#fff; border:1px solid #eee; border-radius:var(--radius); padding:18px; text-align:center; box-shadow:var(--shadow-sm)}
        .service-icon{width:64px; height:64px; border-radius:50%; margin:0 auto 10px; display:grid; place-items:center; background:rgba(212,59,59,.12)}

        .newsletter{background:var(--p-red); color:#fff}
        .newsletter .row{display:grid; grid-template-columns:1.2fr 1fr; gap:20px; align-items:center}
        @media (max-width: 860px){.newsletter .row{grid-template-columns:1fr; text-align:center}}
        .newsletter .field{display:flex; gap:0}
        .newsletter input{flex:1; padding:12px 14px; border:0; outline:0; border-radius:10px 0 0 10px}
        .newsletter button{border-radius:0 10px 10px 0; background:#111; color:#fff; border:0; padding:12px 16px; font-weight:800}
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>
<!-- NAVBAR -->
<nav class="navbar">
    <a class="logo" href="index.php"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" alt="Paristanbul"></a>
    <ul class="nav-links">
        <li><a href="index.php" class="active">Accueil</a></li>
        <li><a href="quiSommesNous.html">Notre histoire</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="postuler.php">Postuler</a></li>
    </ul>
    <div class="nav-actions">
        <a href="pageInscription.php" class="btn btn-light">Inscription</a>
        <a href="pageConnexion.php" class="btn btn-dark">Connexion</a>
    </div>
</nav>

<!-- HERO -->
<header class="hero">
    <div class="container position-relative">
        <h1 class="fw-bold" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);">Nos Magasins</h1>
        <p class="mb-4" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);">Retrouvez tous nos points de vente près de chez vous</p>

    </div>

    <button id="locateMe" class="btn" style="background:#fff;color:var(--p-red);border:2px solid #fff">📍 Me localiser</button>

    <a href="#liste" class="btn" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);color:#fff;border:2px solid #fff">Voir la liste</a>
        </div>
    </div>
</header>

<!-- BARRE OUTILS -->
<div class="container">
    <div class="toolbar">
        <label class="input" for="search">
            <span>🔎</span>
            <input id="search" type="text" placeholder="Rechercher par ville ou code postal…"/>
        </label>
        <button id="resetFilters" class="chip" title="Effacer la recherche">Réinitialiser</button>
        <button id="toggleSort" class="chip" title="Trier par proximité">Trier par distance</button>
    </div>
</div>

<!-- LAYOUT: MAP + LIST -->
<main class="container section layout">
    <div>
        <div id="map" aria-label="Carte des magasins"></div>
    </div>

    <section id="liste" class="cards" aria-live="polite"></section>
</main>

<!-- SERVICES EN MAGASIN -->
<section class="section">
    <div class="container">
        <h3>Nos services en magasin</h3>
        <div class="grid-4">
            <article class="service-card">
                <div class="service-icon">🛒</div>
                <h4>Drive</h4>
                <p>Commandez en ligne et récupérez vos courses.</p>
            </article>
            <article class="service-card">
                <div class="service-icon">🚚</div>
                <h4>Livraison</h4>
                <p>Faites-vous livrer à domicile.</p>
            </article>
            <article class="service-card">
                <div class="service-icon">🎁</div>
                <h4>Cadeaux</h4>
                <p>Service d’emballage cadeaux.</p>
            </article>
            <article class="service-card">
                <div class="service-icon">👜</div>
                <h4>Click & Collect</h4>
                <p>Commandez en ligne et récupérez en 2h.</p>
            </article>
        </div>
    </div>
</section>

<!-- NEWSLETTER -->
<section class="newsletter section">
    <div class="container">
        <div class="row">
            <div>
                <h3>Restez informé !</h3>
                <p>Recevez nos actualités et offres spéciales.</p>
            </div>
            <form class="field" onsubmit="event.preventDefault();alert('Merci ! Vous êtes inscrit.');">
                <input type="email" placeholder="Votre adresse email" required>
                <button type="submit">S’inscrire</button>
            </form>
        </div>
    </div>
</section>

<!-- PHP (à intégrer dans un .php si nécessaire) -->
<!--
  <?php
// Recommandé: utf8mb4 et erreur en exception
$pdo = new PDO(
    'mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8mb4',
    'root','',
    [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
);

$coords = [
    'Nogent-sur-Oise' => [49.278948, 2.464688],
    'Villemomble'     => [48.8890,  2.5040],
    'Bondy'           => [48.9022,  2.48278],
    'Drancy'          => [48.924298,2.445676],
    'Villiers-le-Bel' => [49.0094,  2.3911],
];

$magasins = [];
foreach($pdo->query('SELECT * FROM magasins') as $row){
    $ville = trim($row['ville_magasin'] ?? '');
    [$lat,$lon] = $coords[$ville] ?? [48.8566, 2.3522]; // Paris fallback
    $magasins[] = [
        'nom'      => 'Paristanbul ' . $ville,
        'adresse'  => ($row['rue'] ?? '') . ', ' . ($row['cp'] ?? '') . ' ' . $ville,
        'tel'      => $row['num_tel'] ?? '',
        'horaires' => ($row['jours_ouverture'] ?? '') . ' : ' . substr($row['horaire_ouverture'] ?? '',0,5) . '–' . substr($row['horaire_fermeture'] ?? '',0,5),
        'lat'      => $lat,
        'lon'      => $lon,
        'services' => ['Boucherie','Épicerie'],
        'image'    => $row['image'] ?? ''
    ];
}
echo '<script>window.__MAGASINS__ = ' . json_encode($magasins, JSON_UNESCAPED_UNICODE) . ';</script>';
?>
  -->

<!-- JS applicatif (fonctionne avec window.__MAGASINS__ ou fallback démo) -->
<script>
    const magasins = window.__MAGASINS__ ?? [
        {nom:'Paristanbul Drancy', adresse:'12 Rue X, 93700 Drancy', tel:'01 23 45 67 89', horaires:'Lun–Sam : 09:00–20:00', lat:48.924298, lon:2.445676, services:['Boucherie','Épicerie','Parking'], image:'../assets/img/magasin_drancy.jpg'},
        {nom:'Paristanbul Bondy',  adresse:'5 Av. Y, 93140 Bondy',   tel:'01 23 45 67 80', horaires:'Lun–Sam : 09:00–20:00', lat:48.9022,   lon:2.48278,  services:['Épicerie','Click&Collect'], image:'../assets/img/magasin_bondy.jpg'},
    ];

    // Haversine pour distance en km
    function distanceKm(aLat,aLon,bLat,bLon){
        const R=6371, toRad=x=>x*Math.PI/180;
        const dLat=toRad(bLat-aLat), dLon=toRad(bLon-aLon);
        const s=Math.sin(dLat/2)**2 + Math.cos(toRad(aLat))*Math.cos(toRad(bLat))*Math.sin(dLon/2)**2;
        return +(R*2*Math.atan2(Math.sqrt(s),Math.sqrt(1-s))).toFixed(1);
    }

    // Rendu des cartes magasins
    const $list = document.getElementById('liste');
    function badge(service){
        const s = service.toLowerCase();
        if(s.includes('bouch')) return '<span class="badge b-red">Boucherie</span>';
        if(s.includes('collect')) return '<span class="badge b-blue">Click & Collect</span>';
        if(s.includes('park')) return '<span class="badge b-blue">Parking</span>';
        if(s.includes('bio')) return '<span class="badge b-gold">Bio</span>';
        return '<span class="badge b-green">' + service + '</span>';
    }

    function renderCards(items, user){
        $list.innerHTML = '';
        items.forEach(m=>{
            const d = user ? `${distanceKm(user.lat,user.lon,m.lat,m.lon)} km` : '—';
            const gmap = user
                ? `https://www.google.com/maps/dir/?api=1&origin=${user.lat},${user.lon}&destination=${m.lat},${m.lon}`
                : `https://www.google.com/maps/dir/?api=1&destination=${m.lat},${m.lon}`;
            const services = (m.services||[]).map(badge).join('\n');
            const safeImg = m.image || 'https://placehold.co/800x450?text=Paristanbul';

            $list.insertAdjacentHTML('beforeend', `
          <article class="card">
            <div class="card-head">
              <span class="badge-open">Ouvert</span>
              <div class="image-wrap"><img src="${safeImg}" alt="${m.nom}"></div>
            </div>
            <div class="card-body">
              <h3 class="title">${m.nom}</h3>
              <div class="meta">
                <div><i>📍</i> ${m.adresse}</div>
                <div><i>🕒</i> ${m.horaires || 'Horaires à venir'}</div>
                <div><i>📞</i> ${m.tel || '—'}</div>
                <div><i>🚶</i> ${d}</div>
              </div>
              <div class="services">${services}</div>
              <div class="actions">
                <a target="_blank" href="${gmap}" class="btn" style="background:var(--p-red);color:#fff">Itinéraire</a>
                <button class="btn" style="background:#fff;border:1px solid #e9e9ef" onclick="alert('Page détail à venir')">Détails</button>
              </div>
            </div>
          </article>
        `);
        });
    }

    // Carte Leaflet
    const map = L.map('map');
    const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);
    const group = L.featureGroup();

    function fitToMarkers(){
        if(group.getLayers().length){
            map.fitBounds(group.getBounds().pad(0.2));
        }else{
            map.setView([46.6031, 1.8883], 6); // France
        }
    }

    function drawMarkers(items){
        group.clearLayers();
        items.forEach(m=>{
            const marker = L.marker([m.lat,m.lon]).bindPopup(`<strong>${m.nom}</strong><br>${m.adresse}`);
            group.addLayer(marker);
        });
        group.addTo(map);
        fitToMarkers();
    }

    // Géolocalisation utilisateur
    let userLoc = null;
    document.getElementById('locateMe').addEventListener('click',()=>{
        if(!navigator.geolocation) return alert('Votre navigateur ne supporte pas la géolocalisation.');
        navigator.geolocation.getCurrentPosition(pos=>{
            userLoc = {lat:pos.coords.latitude, lon:pos.coords.longitude};
            const userMarker = L.circleMarker([userLoc.lat,userLoc.lon], {radius:8, weight:2, color:'#111', fillColor:'#2dd4bf', fillOpacity:0.9}).bindPopup('📍 Vous êtes ici');
            userMarker.addTo(map).openPopup();
            map.setView([userLoc.lat,userLoc.lon], 11);
            // tri par distance si activé
            if(sortByDistance){
                const sorted = [...magasins].sort((a,b)=>distanceKm(userLoc.lat,userLoc.lon,a.lat,a.lon)-distanceKm(userLoc.lat,userLoc.lon,b.lat,b.lon));
                renderCards(sorted, userLoc); drawMarkers(sorted);
            }else{
                renderCards(magasins, userLoc);
            }
        }, ()=> alert('Impossible de vous localiser.'))
    });

    // Recherche et tri
    const $search = document.getElementById('search');
    let sortByDistance = false;

    function applyFilters(){
        const q = ($search.value||'').toLowerCase().trim();
        let items = magasins.filter(m => !q || (m.nom+m.adresse).toLowerCase().includes(q));
        if(sortByDistance && userLoc){
            items = items.sort((a,b)=>distanceKm(userLoc.lat,userLoc.lon,a.lat,a.lon)-distanceKm(userLoc.lat,userLoc.lon,b.lat,b.lon));
        }
        renderCards(items, userLoc);
        drawMarkers(items);
    }

    $search.addEventListener('input', applyFilters);
    document.getElementById('resetFilters').addEventListener('click', ()=>{ $search.value=''; applyFilters(); });
    document.getElementById('toggleSort').addEventListener('click', ()=>{ sortByDistance = !sortByDistance; applyFilters(); });

    // Initial draw
    renderCards(magasins, null);
    drawMarkers(magasins);
</script>
</body>
</html>
