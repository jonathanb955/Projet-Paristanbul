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
    <link rel="web site icon" type="png" href="https://play-lh.googleusercontent.com/4-hTf32960CWp7N_cBSNN7UnH3UNHMzgye3wGzXqSp69-iAc7-88jwc1jPlkeqDktLE">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">



</head>

<body>
<header>
    <button class="btn btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions" style="color: black;font-size: 30px "><i class="bi bi-justify"></i></button>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="width: 250px">
        <div class="offcanvas-header"  style="background-color:#3a3939">
            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel"><u> <div class="logo" ><img src="../assets/img/LOGO-PARISTANBUL-300x94.png" style="width: 160px"></div>
                    <div class="d-flex justify-content-center align-items-center position-relative ">
                        <div class="btn-group position-absolute end-0 me-3">


                        </div>



                    </div></u></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div style="height: 3px; width: 100%; background-color:  #e0c097; margin: 0 auto;"></div>

        <div class="offcanvas-body">

            <div class="row">
                <div class="col-4">
                    <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4 border-end">
                        <nav class="nav  flex-column">
                            <a class="nav-link" style="color:  darkgrey;   text-decoration: none "><i class="bi bi-caret-right-fill"></i><strong><u>Consulter en ligne</u></strong></a>
                            <a class="nav-link"   href="#item-5" style="color: #a0522d;   text-decoration: none"><strong><u>Catalogue</u></strong> <i class="bi bi-book-half"></i></a>

                            <a class="nav-link" href="#item-1"><strong><u>Aliments</u></strong></a>
                            <nav class="nav nav flex-column">
                                <a class="nav-link ms-3 my-1" href="#item-1-1">Frais</a>
                                <a class="nav-link ms-3 my-1" href="#item-1-2">Secs</a>

                            </nav>

                            <a class="nav-link" href="#item-2"><strong><u>Produits</u></strong></a>
                            <nav class="nav  flex-column">
                                <a class="nav-link ms-3 my-1" href="#item-2-1">Hygiènes</a>
                                <a class="nav-link ms-3 my-1" href="#item-2-2">Contenants</a>
                            </nav>

                            <a class="nav-link" href="#item-3"><strong><u>Boissons</u></strong></a>
                            <nav class="nav  flex-column">
                                <a class="nav-link ms-3 my-1" href="#item-3-1">Bières</a>
                                <a class="nav-link ms-3 my-1" href="#item-3-2">Vins</a>
                            </nav>

                            <a class="nav-link" style="color:  darkgrey;   text-decoration: none "><i class="bi bi-caret-right-fill"></i><strong><u>À propos de Paristanbul</u></strong></a>
                            <a class="nav-link"   <a href="#appli" style="color: #a0522d;   text-decoration: none"><strong><u>Télécharger notre application</u></strong> <i class="bi bi-app-indicator"></i></i></a>


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
        <div class="titreCatalogue"  data-aos="zoom-down" data-aos-once="true"><strong> <a href="catalogue.php" style="color:  #a0522d;  text-decoration: none;"> En ce moment, chez Paristanbul</a></strong></div>




        <div class="container">
            <div class="colonne-gauche"  data-aos="fade-right" data-aos-once="true"></div>
            <div class="colonne-droite">
                <div class="bloc-haut" data-aos="fade-left" data-aos-once="true"></div>
                <div class="bloc-bas"  data-aos="fade-left" data-aos-once="true"></div>
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
            <i class="bi bi-book" style="font-size: 25px; color:#a0522d "></i>
            <h3><a href="quiSommesNous.html" style="color: #a0522d ;  text-decoration: none;">À propos de nous</a></h3>
            <p>Paristanbul, fondé par Metin Gultekin en 1993, est une entreprise familiale avec une histoire riche et des valeurs fortes.</p>
        </div>
        <div class="info-box fade-in">
            <i class="bi bi-shop-window" style="font-size: 25px; color:#a0522d "></i>
            <h3><a href="quiSommesNous.html" style="color: #a0522d  ; text-decoration: none;">Nos magasins </a></h3>
            <p>Produits frais, qualité garantie. Retrouvez tout ce dont vous avez besoin chez vous.</p>
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