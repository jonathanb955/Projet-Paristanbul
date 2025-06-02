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
    <input type="text" name="search" placeholder="Rechercher une catégorie..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Rechercher</button>
</form>



<div class="container mt-5">
<h2 class="text-center mb-4">Nos catégories de produits</h2>
<div class="row">
    <?php foreach ($categories as $cat): ?>
        <div class="col-sm-6 col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="<?= htmlspecialchars($cat['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($cat['nom_categorie']) ?>">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title"><?= htmlspecialchars($cat['nom_categorie']) ?></h5>
                    <a href="?categorie=<?= $cat['id_categorie'] ?>" class="btn btn-primary mt-auto">Voir les produits</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>
</body>
</html>
<style>
    .card {
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        background: #fff;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #333;
    }

    .btn-primary {
        background-color: #a0522d;
        border-color: #a0522d;
        transition: background-color 0.3s;
    }

    .btn-primary:hover {
        background-color: #7b3e1b;
        border-color: #7b3e1b;
    }

</style>


