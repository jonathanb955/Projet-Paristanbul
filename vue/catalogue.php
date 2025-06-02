<?php

use bdd\Bdd;

session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
$connecte = isset($_SESSION['connexion']) && $_SESSION['connexion'] === true;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue des produits</title>
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

<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Récupération du terme de recherche
$search = isset($_GET['search']) ? $_GET['search'] : '';


// Requête selon qu'il y a une recherche ou non

if (!empty($search)) {
    $req = $pdo->prepare('SELECT nom_produit, photo FROM produits WHERE nom_produit LIKE :search');
    $req->execute(['search' => '%' . $search . '%']);
} else {
    $req = $pdo->query('SELECT nom_produit, photo FROM produits ');
}
?>



<form action="" method="GET">
    <input type="text" name="search" placeholder="Rechercher un produit..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Rechercher</button>
</form>


<div class="container mt-4">
    <h1 class="text-center mb-4"><u>Nos produits disponibles</u></h1>
    <div id="produit-container" class="catalogue">
        <?php
        if ($req->rowCount() > 0) {
            while ($produits = $req->fetch(PDO::FETCH_ASSOC)) {
                $nom = htmlspecialchars($produits['nom_produit']);
                $photo = htmlspecialchars($produits['photo']);
                echo '<div class="film-card produit-item">';
                echo '<img src="' . $photo . '" alt="' . $nom . '" class="produit-photo">';
                echo '<h5>' . $nom . '</h5>';
                echo '<form action="reservation.php" method="get">
                    <button type="submit" class="btn btn-dark mt-2" name="destination" value="' . $nom . '">Voir le produit</button>
                  </form>';
                echo '</div>';
            }
        }
        ?>
    </div>

    <div class="text-center mt-4">
        <div id="counter" style="margin-bottom: 10px; font-weight: bold;"></div>
        <button id="loadMoreBtn" class="btn btn-primary">Charger plus de produits</button>
    </div>
</div>

</body>
</html>


<script>
    const produits = Array.from(document.querySelectorAll('.film-card'));
    const container = document.getElementById('produit-container');
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    const counter = document.getElementById('counter');
    const itemsPerPage = 8;
    let itemsToShow = itemsPerPage;

    // Cache initial state: on retire tous les produits du DOM sauf les premiers affichés
    function init() {
        container.innerHTML = '';
        produits.forEach((item, index) => {
            if (index < itemsToShow) {
                container.appendChild(item);
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
        updateCounter();
        toggleButton();
    }

    function updateCounter() {
        const total = produits.length;
        const shown = Math.min(itemsToShow, total);
        counter.textContent = `Affichage ${shown} sur ${total} produits`;
    }

    function toggleButton() {
        if (itemsToShow >= produits.length) {
            loadMoreBtn.style.display = 'none';
        } else {
            loadMoreBtn.style.display = 'inline-block';
        }
    }

    loadMoreBtn.addEventListener('click', () => {
        itemsToShow += itemsPerPage;
        if (itemsToShow > produits.length) {
            itemsToShow = produits.length;
        }
        // Afficher les nouveaux produits
        for(let i = 0; i < itemsToShow; i++) {
            produits[i].style.display = 'block';
            if (!container.contains(produits[i])) {
                container.appendChild(produits[i]);
            }
        }
        updateCounter();
        toggleButton();
    });

    init();
</script>

<style>
    .catalogue {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .film-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        display: none; /* caché par défaut, affiché par JS */
    }

    .film-card:hover {
        transform: scale(1.05);
    }
</style>