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
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://previews.123rf.com/images/jovanas/jovanas1602/jovanas160201149/52031915-logo-avi%C3%B3n-volando-alrededor-del-planeta-tierra-azul.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- ✅ UNIQUE CSS POUR LA BARRE HAUT GAUCHE -->
</head>
<!-- ... tes autres <link> comme Bootstrap, police Inter, etc. -->

<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .offcanvas-start {
        width: 275px !important;
        background-color: #fff !important;
        padding: 1rem;
    }

    .offcanvas-body .nav-link {
        font-size: 16px;
        font-weight: 600;
        color: #333 !important;
        padding: 10px 15px;
        margin: 6px 0;
        border-radius: 8px;
        background-color: transparent !important;
        transition: background-color 0.2s ease-in-out, color 0.2s;
    }

    .offcanvas-body .nav-link:hover,
    .offcanvas-body .nav-link:focus {
        background-color: #f2f2f2 !important;
        color: #0a58ca !important;
    }

    .offcanvas-body .nav .nav-link.ms-3 {
        font-weight: 400;
        background-color: transparent !important;
        margin-left: 1rem;
        color: #555 !important;
    }

    .offcanvas-body .nav .nav-link.ms-3:hover {
        background-color: #f2f2f2 !important;
        color: #0a58ca !important;
    }

    .offcanvas-body .nav-link.active,
    .offcanvas-body .nav-link:active {
        background-color: transparent !important;
        color: #333 !important;
    }
</style>
</head>

<!-- ✅ UNIQUE CSS POUR LA BARRE HAUT GAUCHE -->

<!-- ✅ Style personnalisé pour rétrécir le menu -->
<style>
    .offcanvas-start {
        width: 275px !important;
    }
</style>

<!-- JS Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>

<body>
<header>
    <button class="btn btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" style="color: #a0522d;font-size: 30px "><i class="bi bi-justify"></i></button>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel"><u> <div class="logo" ><img src="../assets/img/LOGO-PARISTANBUL-300x94.png" style="width: 160px"></div>
                    <div class="d-flex justify-content-center align-items-center position-relative ">
                        <div class="btn-group position-absolute end-0 me-3">


                        </div>



                    </div></u></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div style="height: 3px; width: 100%; background-color: lightgrey; margin: 0 auto;"></div>

        <div class="offcanvas-body">

            <div class="row">
                <div class="col-4">
                    <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4 border-end">
                        <nav class="nav nav-pills flex-column">

                            <a class="nav-link" href="#item-1"><strong><u>Boissons</u></strong></a>
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link ms-3 my-1" href="#item-1-1">Basiques</a>
                                <a class="nav-link ms-3 my-1" href="#item-1-2">Asiatiques</a>
                            </nav>

                            <a class="nav-link" href="#item-2"><strong><u>Aliments</u></strong></a>
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link ms-3 my-1" href="#item-2-1">Frais</a>
                                <a class="nav-link ms-3 my-1" href="#item-2-2">Secs</a>
                                <a class="nav-link ms-3 my-1" href="#item-2-3">Surgelés</a>
                            </nav>

                            <a class="nav-link" href="#item-3"><strong><u>Produits</u></strong></a>
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link ms-3 my-1" href="#item-3-1">Hygiènes</a>
                                <a class="nav-link ms-3 my-1" href="#item-3-2">Contenants</a>
                            </nav>

                            <a class="nav-link" href="#item-4"><strong><u>Alcools</u></strong></a>
                            <nav class="nav nav-pills flex-column">
                                <a class="nav-link ms-3 my-1" href="#item-4-1">Bières</a>
                                <a class="nav-link ms-3 my-1" href="#item-4-2">Vins</a>
                            </nav>
                        </nav>
                    </nav>
                </div>
            </div>
        </div>
    </div>

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
            <ul class="dropdown-menu text-center">
                <?php if ($connecte): ?>
                    <span class="dropdown-item-text"><strong>Bienvenue</strong><br><?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?></span>
                    <li><hr class="dropdown-divider"></li>

                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li><a class="dropdown-item" href="../../Projet-Vol/vue/pageAdmin.php"><i class="bi bi-shield-lock-fill"></i> Espace Admin</a></li>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="../../Projet-Vol/vue/espaceClient.php"><i class="bi bi-person-circle"></i> Espace Client</a></li>
                    <?php endif; ?>

                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="?logout=true"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="vue/pageConnexion.php">Connexion <i class="bi bi-person-bounding-box"></i></a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="vue/pageInscription.php">Inscription <i class="bi bi-person-plus-fill"></i></a></li>
                <?php endif; ?>
            </ul>


        </ul>
    </nav>

</header>






<main>
    <section id="catalogue">
        <div class="titreCatalogue"><strong> <a href="catalogue.php" style="color:  #a0522d;  text-decoration: none;"> En ce moment, chez Paristanbul</a></strong></div>




        <div class="container">
            <div class="colonne-gauche"></div>
            <div class="colonne-droite">
                <div class="bloc-haut"></div>
                <div class="bloc-bas"></div>
            </div>
        </div>

    </section>
    <br>
    <br>
    <br>
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel" style="height: 400px">

        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="../assets/img/maxresdefault.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption custom-caption" style="font-family: 'Times New Roman',serif">
                    <h1>Le supermarché Paristanbul vous souhaite la bienvenue</h1>
                    <p>Nous sommes ravis de vous accueillir.<br>
                        Découvrez nos produits issus du monde entier ainsi que nos services proposés</p>
                </div>
            </div>

            <div class="carousel-item" data-bs-interval="5000">
                <img src="../assets/img/youtube-video-gif%20(1).gif" class="d-block w-100" alt="...">
                <div class="carousel-caption custom-caption " style="font-family: 'Times New Roman',serif">
                    <h2><u style="text-transform: capitalize">Découvrez nos produits</u></h2>
                    <p>Découvrez les saveurs du monde entier grâce à nos magasins <br></p>
                </div>
            </div>

            <div class="carousel-item" data-bs-interval="5000">
                <img src="../assets/img/IMG_2172.jpg" class="d-block w-100" alt="...">
                <div class="carousel-caption custom-text" style="font-family: 'Times New Roman',serif">
                    <h2><u>A REMPLIR</u></h2>
                    <p>DESCRIPTION A REMPLIR.</p>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <br>

    <section id="apropos">
        <div class="info-box fade-in">
            <h3><a href="quiSommesNous.html" style="color: #a0522d ;  text-decoration: none;">À propos de nous</a></h3>
            <p>Paristanbul, fondé par Metin Gultekin en 1993, est une entreprise familiale avec une histoire riche et des valeurs fortes.</p>
        </div>
        <div class="info-box fade-in">
            <h3><a href="quiSommesNous.html" style="color: #a0522d  ; text-decoration: none;">Nos magasins </a></h3>
            <p>Produits frais, qualité garantie. Retrouvez tout ce dont vous avez besoin chez vous.</p>
        </div>
        <div class="info-box fade-in">
            <h3><a href="quiSommesNous.html" style="color: #a0522d;   text-decoration: none;">Nous rejoindre </a></h3>
            <p>Envie de faire partie de l’aventure Paristanbul ? Postulez dès maintenant !</p>
        </div> <div class="info-box fade-in">
            <h3> Notre application</h3>
            <p>Faites des économies en téléchargeant l'application !</p>
            <a href="https://play.google.com/store/apps/details?id=com.akead.paristanbul" class="text-success google-play"><i class="bi bi-google-play"></i></a>
            <a href="https://apps.apple.com/id/app/paristanbul-plus/id6743162682" class="text-primary apple-store"><i class="bi bi-apple"></i></a>
        </div>
    </section>

    <section id="reseauxsociaux">
        <h2>Nous suivre</h2>
        <p>
            <a href="https://youtu.be/tVr152vEHNY?si=eubKRBimOqZoJhPe" class="text-danger"><i class="bi bi-youtube"></i></a>
            <a href="https://www.facebook.com/supermarcheparistanbul/?locale=fr_FR" class="text-primary"><i class="bi bi-facebook"></i></a>
            <a href="https://www.instagram.com/paristanbul_supermarche/" class="text-warning"><i class="bi bi-instagram" style="color: #c32aa3"></i></a>
            <a href="https://www.instagram.com/paristanbul_supermarche/" class="text-dark"><i class="bi bi-tiktok"></i></a>
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
    window.addEventListener('scroll', () => {
        document.querySelectorAll('.fade-in').forEach(el => {
            if (el.getBoundingClientRect().top < window.innerHeight - 100) {
                el.classList.add('visible');
            }
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>