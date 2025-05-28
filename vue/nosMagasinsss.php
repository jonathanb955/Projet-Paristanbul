<?php

use bdd\Bdd;


session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$connecte = isset($_SESSION['connexion']) && $_SESSION['connexion'] === true;

if ($connecte && isset($_SESSION['utilisateur']) && isset($_SESSION['utilisateur']['role'])) {
    $_SESSION['role'] = $_SESSION['utilisateur']['role'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paristanbul</title>

    <!-- Feuilles de style -->
    <link rel="stylesheet" href="../assets/css/nosMagasins.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://play-lh.googleusercontent.com/4-hTf32960CWp7N_cBSNN7UnH3UNHMzgye3wGzXqSp69-iAc7-88jwc1jPlkeqDktLE">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/catalogue.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://previews.123rf.com/images/jovanas/jovanas1602/jovanas160201149/52031915-logo-avi%C3%B3n-volando-alrededor-del-planeta-tierra-azul.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


</head>

<body>


<header>
    <div class="d-flex justify-content-center align-items-center position-relative">

    </div>

    <form action="index.php" method="get" class="position-absolute start-0 ms-3">
        <button type="submit" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Retour
        </button>
    </form>

    <div class="logo"><a href="index.php"><img src="../assets/img/LOGO-PARISTANBUL-300x94.png"></a></div>
    <div class="d-flex justify-content-center align-items-center position-relative ">
        <div class="btn-group position-absolute end-0 me-3">


        </div>

    </div>

    <nav>
        <ul>

            <li><a href="#catalogue">Catalogue</a></li>
            <li><a href="#reseauxsociaux">Nous suivre</a></li>
            <li><a href="#apropos">Informations</a></li>
            <button type="button" class="btn btn-black dropdown-toggle" style="color: #a0522d" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-square"></i>
            </button>


        </ul>


        </ul>
    </nav>

</header>
<div class="catalogue">

    <?php

    $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');


    $search = isset($_GET['search']) ? $_GET['search'] : '';


    if ($search) {
        $req = $pdo->prepare('
        SELECT *
        FROM magasins
        WHERE magasins.ville_magasin LIKE :search
        
    ');
        $req->execute(['search' => '%' . $search . '%']);
    } else {
        $req = $pdo->prepare('SELECT * FROM magasins ');
        ($magasins = $req->execute());
        $magasins = $req->fetchAll();
        foreach ($magasins as $magasin) {
            $ville = $magasin['ville_magasin'];
            $rue = $magasin['rue'];
            $cp = $magasin['cp'];
            $image = $magasin['image'];

            // Choix du fichier selon la ville
            switch (strtolower($ville)) {
                case 'villiers-le-bel':
                    $fichier = 'magasinVillierLeBel1.php';
                    break;
                case 'drancy':
                    $fichier = 'magasinDrancy.php';
                    break;
                case 'bondy':
                    $fichier = 'magasinBondy.php';
                    break;
                case 'villemomble':
                    $fichier = 'magasinVillemomble.php';
                    break;
                case 'nogent-sur-oise':
                    $fichier = 'magasinNogentSurOise.php';
                    break;
                case 'paris':
                    $fichier = 'magasinVillierLeBel2.php';
                    break;
            }

            echo '<div class="film-card">';
            echo '<img src="' . htmlspecialchars($image) . '" alt="Photo de ' . htmlspecialchars($ville) . '" class="magasins-photo">';
            echo '<div class="film-info">';
            echo '<u><h2>Magasin : ' . htmlspecialchars($ville) . '</h2></u>';
            echo '<p>' . htmlspecialchars($rue) . " " . htmlspecialchars($cp) . '</p>';
            echo '<form action="../../vue/villeMagasin/' . $fichier . '" method="get">';
            echo '<button type="submit" class="btn btn-dark" value="' . htmlspecialchars($ville) . '">Voir +</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }



    }





    ?>

</div>



<main>

    <div class="recherche">
        <form id="search-form">
            <div class="trouver-magasin">
                <label for="magasin" class="form-label">Trouver un magasin</label>
                <div class="search-group">
                    <input type="search" name="magasin" id="magasin" class="form-control" placeholder="Saisir une ville, un code postal...">
                    <button type="submit" class="search-button" aria-label="Rechercher">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>


        <div id="map">
            <!-- Ici la carte -->
        </div>

    </div>





    <br>

    <section id="apropos">
        <div class="info-box fade-in">
            <i class="bi bi-book" style="font-size: 25px; color:#a0522d "></i>
            <h3><a href="quiSommesNous.html" style="color: #a0522d ;  text-decoration: none;">À propos de nous</a></h3>
            <p>Paristanbul, fondé par Metin Gultekin en 1993, est une entreprise familiale avec une histoire riche et des valeurs fortes.</p>
        </div>
        <div class="info-box fade-in">
            <i class="bi bi-person-fill-add" style="font-size: 25px; color:#a0522d " ></i>
            <h3><a href="quiSommesNous.html" style="color: #a0522d;   text-decoration: none;">Nous rejoindre </a></h3>
            <p>Envie de faire partie de l’aventure Paristanbul ? Postulez dès maintenant !</p>
        </div>



        <section id="appli">
            <div class="info-box fade-in" data-bs-toggle="modal" data-bs-target="#appliModal">
                <i class="bi bi-app-indicator" style="font-size: 25px; color:#a0522d;"></i><br>
                <h3>Notre application</h3>
                <p>Faites des économies en téléchargeant l'application !</p>


            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="appliModal" tabindex="-1" aria-labelledby="appliModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appliModalLabel" style="p">Télécharger l'application</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p ><i class="bi bi-google-play text-success" style="font-size: 20px"></i><a href="https://play.google.com/store/apps/details?id=com.akead.paristanbul" target="_blank" style="font-size: 20px; color: green; text-decoration: none ">Google Play</a></p>
                        <p><i class="bi bi-apple text-primary"  style="font-size: 20px"></i><a href="https://apps.apple.com/id/app/paristanbul-plus/id6743162682" target="_blank"  style="font-size: 20px;  text-decoration: none">Apple Store</a></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS (obligatoire !) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Bootstrap Icons si tu veux les icônes -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">




        <section id="reseauxsociaux">
            <div class="titreReseaux" data-aos="fade-up"  data-aos-once="true"><h2>Nous suivre</h2></div>
            <p>
                <a href="https://youtu.be/tVr152vEHNY?si=eubKRBimOqZoJhPe" class="text-danger"  data-aos="fade-up"  data-aos-once="true"><i class="bi bi-youtube"></i></a>
                <a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" class="text-primary"  data-aos="fade-up"  data-aos-once="true"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/paristanbul_supermarche/" class="text-warning" data-aos="fade-up"  data-aos-once="true"><i class="bi bi-instagram" style="color: #c32aa3" ></i></a>
                <a href="https://www.instagram.com/paristanbul_supermarche/" class="text-dark" data-aos="fade-up"  data-aos-once="true"><i class="bi bi-tiktok" ></i></a>
            </p>
        </section>



        <br>
        <br>
        <br>

</main>
<footer>
    <p>&copy; 2025 Paristanbul. Tous droits réservés.</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const elements = document.querySelectorAll('.fade-in');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Pour déclencher une seule fois :
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        elements.forEach(el => observer.observe(el));
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init();
</script>

</body>
</html>


<!------- Open street map ------------>
<!-- CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin="" />


<!-- Fichiers Javascript -->
<script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>

<!-- Initialise la map -->
<script>
    var villes = {
        "Villiers-le-Bel": { "lat": 48.999975, "lon": 2.3907978 },
        "PARISTANBUL DRANCY": { "lat": 48.929943, "lon": 2.431965 },
        "PARISTANBUL BONDY": { "lat": 48.905894, "lon": 2.481594 },
        "SUPERMARCHE PARISTANBUL VILLEMOMBLE": { "lat": 48.8807913, "lon": 2.5018502 },
        "PARISTANBUL NOGENT-SUR-OISE": { "lat": 49.2860566, "lon": 2.4589897 },
    };

    let macarte;

    document.addEventListener('DOMContentLoaded', function () {
        initMap();

        // ✅ Handler sur le vrai <form>
        document.getElementById('search-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const search = document.getElementById('magasin').value.trim().toLowerCase();

            let found = false;
            for (const ville in villes) {
                if (ville.toLowerCase().includes(search)) {
                    const { lat, lon } = villes[ville];
                    macarte.setView([lat, lon], 15); // Zoom sur la ville
                    L.popup()
                        .setLatLng([lat, lon])
                        .setContent(`<strong>${ville}</strong>`)
                        .openOn(macarte);
                    found = true;
                    break;
                }
            }

            if (!found) {
                alert("Magasin introuvable.");
            }
        });
    });

    function initMap() {
        var lat = villes["Villiers-le-Bel"].lat;
        var lon = villes["Villiers-le-Bel"].lon;

        macarte = L.map('map').setView([lat, lon], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
            attribution: 'données © OpenStreetMap/ODbL - rendu OSM France',
            minZoom: 1,
            maxZoom: 20
        }).addTo(macarte);

        for (let ville in villes) {
            let marker = L.marker([villes[ville].lat, villes[ville].lon]).addTo(macarte);
            marker.bindPopup(ville);
        }
    }
</script>