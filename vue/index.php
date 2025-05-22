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
    <link rel="stylesheet" href="../assets/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="web site icon" type="png" href="https://previews.123rf.com/images/jovanas/jovanas1602/jovanas160201149/52031915-logo-avi%C3%B3n-volando-alrededor-del-planeta-tierra-azul.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Libre+Baskerville:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="logo"> <img src="../assets/img/LOGO-PARISTANBUL-300x94.png"></div>
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
        <div class="titreCatalogue"><strong> <a href="catalogue.php" style="color:  #a0522d;"> En ce moment, chez Paristanbul</a></strong></div>


        

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
            <h3><a href="quiSommesNous.html">À propos de nous</a></h3>
            <p>Paristanbul, fondé par Metin Gultekin en 1993, est une entreprise familiale avec une histoire riche et des valeurs fortes.</p>
        </div>
        <div class="info-box fade-in">
            <h3>Nos magasins</h3>
            <p>Produits frais, qualité garantie. Retrouvez tout ce dont vous avez besoin chez vous.</p>
        </div>
        <div class="info-box fade-in">
            <h3>Nous rejoindre</h3>
            <p>Envie de faire partie de l’aventure Paristanbul ? Postulez dès maintenant !</p>
        </div> <div class="info-box fade-in">
            <h3>Notre application</h3>
            <p>Faites des économies en téléchargeant l'application !</p>
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