<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nos Magasins - Paristanbul</title>
<link href="../assets/css/nosMagasins.css" rel="stylesheet">
    <!-- Bootstrap + Leaflet + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Custom Style -->

</head>
<header>
    <nav class="navbar">
        <div class="logo"><a href="index.php"><img src="../assets/img/paristanbul_logo_1200x350-1024x299.png" style="width: 300px"></a></div>
        <ul class="nav-links">
            <li><a href="index.php" class="active">Accueil</a></li>
            <li><a href="quiSommesNous.html">Notre histoire</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="postuler.php">Postuler</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="pageInscription.php" class="btn-light">Inscription</a>
            <a href="pageConnexion.php" class="btn-dark">Connexion</a>
        </div>
    </nav>
</header>

<body>

<!-- HEADER -->
<section class="text-white text-center py-5 position-relative" style="background: linear-gradient(to bottom right,#003366, white,red); overflow: hidden;">
    <!-- Cercle gauche -->
    <div style="position: absolute; width: 250px; height: 250px; background-color: rgba(255,255,255,0.05); border-radius: 50%; top: 0; left: 15%; transform: translateX(-50%);"></div>

    <!-- Cercle droit -->
    <div style="position: absolute; width: 300px; height: 300px; background-color: rgba(255,255,255,0.05); border-radius: 50%; top: 0; right: 10%; transform: translateX(50%);"></div>

    <div class="container position-relative">
        <h1 class="fw-bold" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);">Nos Magasins</h1>
        <p class="mb-4" style="text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);">Retrouvez tous nos points de vente près de chez vous</p>
        <button id="locateMe" class="btn btn-light text-danger fw-semibold px-4 py-2 rounded-pill">
            <i class="bi bi-crosshair"></i> Me localiser
        </button>
    </div>


</section>
<div class="container my-4">
    <div class="row align-items-center justify-content-center">
        <!-- Champ de recherche -->
        <div class="col-md-6 mb-2 mb-md-0">
            <div class="input-group">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
                <input type="text" class="form-control border-start-0" placeholder="Rechercher par ville ou code postal...">
            </div>
        </div>

        <!-- Bouton Filtrer -->
        <div class="col-auto">
            <button class="btn btn-outline-secondary">
                <i class="bi bi-funnel"></i> Filtrer
            </button>
        </div>

        <!-- Bouton Rechercher -->
        <div class="col-auto">
            <button class="btn btn-danger">
                Rechercher
            </button>
        </div>
    </div>
</div>


<!-- MAP -->
<div class="container my-4">
    <div id="map"></div>
</div>

<!-- LISTE MAGASINS -->
<div class="container my-5">
    <h2 class="fw-bold mb-4">Nos magasins en France</h2>
    <div class="row" id="magasin-list"></div>
</div>









<!-- SCRIPT -->
<?php
$pdo = new PDO("mysql:host=localhost;dbname=bdd_paristanbul;charset=latin1", "root", "");

// coordonnées GPS par ville
$coords = [
    'Nogent-sur-Oise' => [49.278948, 2.464688],
    'Villemomble' => [48.8890, 2.5040],
    'Bondy' => [48.9022, 2.48278],
    'Drancy' => [48.924298, 2.445676],
    'Villiers-le-Bel' => [49.0094, 2.3911]
];

$magasins = [];

$stmt = $pdo->query("SELECT * FROM magasins");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $ville = trim($row['ville_magasin']);
    $lat = $coords[$ville][0] ?? 48.85;
    $lon = $coords[$ville][1] ?? 2.35;

    $magasins[] = [
        "nom" => "Paristanbul " . $ville,
        "adresse" => $row['rue'] . ", " . $row['cp'] . " " . $ville,
        "tel" => $row['num_tel'],
        "horaires" => $row['jours_ouverture'] . " : " . substr($row['horaire_ouverture'], 0, 5) . "–" . substr($row['horaire_fermeture'], 0, 5),
        "lat" => $lat,
        "lon" => $lon,
        "services" => ["Boucherie", "Épicerie"],
        "color" => "bg-danger",
        "image" => $row['image']
    ];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos magasins</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map { height: 400px; }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Nos magasins</h2>
    <div id="map" class="mb-4 rounded shadow-sm"></div>
    <button id="locateMe" class="btn btn-primary mb-4">📍 Me localiser</button>
    <div class="row" id="magasin-list"></div>
</div>

<!-- SCRIPT -->
<script>
    const magasins = <?php echo json_encode($magasins, JSON_UNESCAPED_UNICODE); ?>;

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
        return (R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a))).toFixed(1);
    }
    function renderCards(userLat = null, userLon = null) {
        const container = document.getElementById('magasin-list');
        container.innerHTML = '';

        magasins.forEach(magasin => {
            const distance = (userLat && userLon) ? `${calculateDistance(userLat, userLon, magasin.lat, magasin.lon)} km` : '—';
            const itineraire = (userLat && userLon)
                ? `https://www.google.com/maps/dir/?api=1&origin=${userLat},${userLon}&destination=${magasin.lat},${magasin.lon}`
                : `https://www.google.com/maps/dir/?api=1&destination=${magasin.lat},${magasin.lon}`;

            const servicesHtml = magasin.services.map(service => {
                let color = 'bg-secondary text-white';
                if (service === 'Drive') color = 'bg-success text-dark';
                if (service === 'Poissonnerie') color = 'bg-warning text-dark';
                if (service === 'Boucherie') color = 'bg-purple text-white';
                if (service === 'Bio') color = 'bg-warning text-dark';
                if (service === 'Parking') color = 'bg-primary text-white';
                return `<span class="badge ${color} me-1">${service}</span>`;
            }).join('');

            container.innerHTML += `
        <div class="col-md-4 mb-4">
          <div class="magasin-card border rounded shadow-sm">
            <div class="p-4 ${magasin.color} bg-opacity-10 text-center position-relative">
              <div class="image-wrapper mb-3">
                <img src="${magasin.image}" class="img-fluid" alt="Image magasin">
              </div>
              <span class="badge bg-success position-absolute top-0 end-0 m-2">Ouvert</span>
            </div>
            <div class="p-3 bg-white">
              <h5 class="fw-bold">${magasin.nom}</h5>
              <p class="mb-1 text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> ${magasin.adresse}</p>
              <p class="mb-1"><i class="bi bi-clock text-danger"></i> ${magasin.horaires}</p>
              <p class="mb-1"><i class="bi bi-telephone text-danger"></i> ${magasin.tel}</p>
              <p class="mb-3"><i class="bi bi-person-walking text-danger"></i> ${distance}</p>
              <div class="mb-3">${servicesHtml}</div>
              <div class="d-flex gap-2">
                <a href="${itineraire}" class="btn btn-danger w-50" target="_blank">
                  <i class="bi bi-geo-fill"></i> Itinéraire
                </a>
                <button class="btn btn-outline-dark w-50">
                  <i class="bi bi-eye"></i> Détails
                </button>
              </div>
            </div>
          </div>
        </div>`;
        });
    }

    const map = L.map('map').setView([46.6031, 1.8883], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    magasins.forEach(m => {
        L.marker([m.lat, m.lon]).addTo(map).bindPopup(`<strong>${m.nom}</strong><br>${m.adresse}`);
    });

    renderCards();

    document.getElementById('locateMe').addEventListener('click', () => {
        if (!navigator.geolocation) {
            alert("Votre navigateur ne supporte pas la géolocalisation.");
            return;
        }

        navigator.geolocation.getCurrentPosition(position => {
            const userLat = position.coords.latitude;
            const userLon = position.coords.longitude;

            map.setView([userLat, userLon], 10);
            L.marker([userLat, userLon]).addTo(map).bindPopup("📍 Vous êtes ici").openPopup();

            renderCards(userLat, userLon);
        }, () => {
            alert("Impossible de vous localiser.");
        });
    });
</script>
<!-- Nos services en magasin -->
<section class="bg-light py-5">
    <div class="container">
        <h3 class="fw-bold mb-4">Nos services en magasin</h3>
        <div class="row g-4">

            <!-- Service : Drive -->
            <div class="col-md-3 col-sm-6">
                <div class="text-center bg-white shadow-sm rounded p-4 h-100">
                    <div class="service-icon mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-cart3 fs-4 text-danger"></i>
                    </div>
                    <h6 class="fw-bold">Drive</h6>
                    <p class="mb-0 text-muted">Commandez en ligne et récupérez vos courses</p>
                </div>
            </div>

            <!-- Service : Livraison -->
            <div class="col-md-3 col-sm-6">
                <div class="text-center bg-white shadow-sm rounded p-4 h-100">
                    <div class="service-icon mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-truck fs-4 text-danger"></i>
                    </div>
                    <h6 class="fw-bold">Livraison</h6>
                    <p class="mb-0 text-muted">Faites-vous livrer à domicile</p>
                </div>
            </div>

            <!-- Service : Cadeaux -->
            <div class="col-md-3 col-sm-6">
                <div class="text-center bg-white shadow-sm rounded p-4 h-100">
                    <div class="service-icon mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-gift fs-4 text-danger"></i>
                    </div>
                    <h6 class="fw-bold">Cadeaux</h6>
                    <p class="mb-0 text-muted">Service d'emballage cadeaux</p>
                </div>
            </div>

            <!-- Service : Click & Collect -->
            <div class="col-md-3 col-sm-6">
                <div class="text-center bg-white shadow-sm rounded p-4 h-100">
                    <div class="service-icon mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-bag fs-4 text-danger"></i>
                    </div>
                    <h6 class="fw-bold">Click & Collect</h6>
                    <p class="mb-0 text-muted">Commandez en ligne et récupérez en 2h</p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- Restez informé -->
<section class="bg-danger text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <h5 class="fw-bold">Restez informé !</h5>
                <p class="mb-0">Recevez nos actualités et offres spéciales</p>
            </div>
            <div class="col-md-6">
                <form class="d-flex">
                    <input type="email" class="form-control rounded-0 rounded-start" placeholder="Votre adresse email">
                    <button type="submit" class="btn btn-dark rounded-0 rounded-end">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>
</section>

</body>
</html>
