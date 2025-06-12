
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catalogue des produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fefefe;
            margin: 0;
            padding: 0;
        }

        header {
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
            border-bottom: 1px solid #eee;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #1d1d1f;
        }

        .logo i {
            color: #cc3d3d;
            margin-right: 8px;
        }

        .nav-links a {
            margin: 0 12px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav-links a.active {
            color: #cc3d3d;
        }

        .search-cart {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .search-bar {
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 999px;
            width: 250px;
        }

        .search-bar::placeholder {
            color: #aaa;
        }

        .cart-btn {
            background-color: #cc3d3d;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 18px;
        }

        .hero {
            background-color: #6c2320;
            padding: 60px 30px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before, .hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .hero::before {
            width: 300px;
            height: 300px;
            top: 0;
            left: 10%;
        }

        .hero::after {
            width: 400px;
            height: 400px;
            bottom: 0;
            right: 10%;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        .hero p {
            font-size: 1.1rem;
            margin-top: 10px;
        }

        .hero .btn {
            margin-top: 30px;
            background: white;
            color: #cc3d3d;
            font-weight: 600;
            padding: 10px 25px;
            border-radius: 50px;
        }

        .categories {
            padding: 40px 30px;
            background-color: #fefefe;
        }

        .categories h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .category-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .category-tags .btn {
            border-radius: 999px;
            font-weight: 600;
            padding: 8px 18px;
        }

        .btn-primary {
            background-color: #cc3d3d;
            border: none;
        }

        .btn-outline-secondary {
            background-color: #e6e6ea;
            border: none;
            color: #333;
        }
    </style>
</head>
<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');

if (!empty($search)) {
// Recherche par mot-clé
    $req = $pdo->prepare('SELECT nom_produit, photo FROM produits WHERE nom_produit LIKE :search');
    $req->execute(['search' => '%' . $search . '%']);

} elseif (isset($_GET['categorie'], $_GET['souscategorie'])) {
// Affichage par catégorie + sous-catégorie
    $categorie = $_GET['categorie'];
    $souscategorie = $_GET['souscategorie'];

    $req = $pdo->prepare('SELECT nom_produit, photo FROM produits WHERE ref_categorie = :cat AND ref_sous_categorie = :souscat');
    $req->execute(['cat' => $categorie, 'souscat' => $souscategorie]);

} else {
// Par défaut : afficher tous les produits
    $req = $pdo->query('SELECT nom_produit, photo FROM produits');
}
?>
<body>

<header>
    <div class="logo">
        <i class="bi bi-bag-fill"></i> SuperMarché
    </div>
    <div class="nav-links">
        <a href="#">Accueil</a>
        <a href="#" class="active">Catalogue</a>
        <a href="#">Promotions</a>
        <a href="#">Contact</a>
    </div>
    <div class="search-cart">
        <input class="search-bar" type="text" placeholder="🔍 Rechercher...">
        <button class="cart-btn"><i class="bi bi-cart"></i></button>
    </div>
</header>

<section class="hero">
    <h1>Catalogue des produits</h1>
    <p>Découvrez notre sélection de produits frais et de qualité</p>
    <button class="btn">Voir les promotions</button>
</section>

<section class="categories">
    <h2>Catégories</h2>
    <div class="category-tags">
        <button class="btn btn-primary">Tous les produits</button>
        <button class="btn btn-outline-secondary">Fruits & Légumes</button>
        <button class="btn btn-outline-secondary">Boulangerie</button>
        <button class="btn btn-outline-secondary">Produits laitiers</button>
        <button class="btn btn-outline-secondary">Viandes</button>
        <button class="btn btn-outline-secondary">Boissons</button>
        <button class="btn btn-outline-secondary">Épicerie</button>
    </div>
</section>

<div class="col-md-3 d-flex">
    <div class="card w-100 h-100 d-flex flex-column">
        <div class="position-relative text-center p-4 rounded-top bg-light" style="min-height: 130px;">
            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-20%</span>
            <div class="fs-1">●</div>
        </div>
        <div class="card-body d-flex flex-column justify-content-between flex-grow-1">
            <div>
                <?php
                if ($req->rowCount() > 0) {
                    while ($produits = $req->fetch(PDO::FETCH_ASSOC)) {
                        $nom = htmlspecialchars($produits['nom_produit']);
                        $photo = htmlspecialchars($produits['photo']);
                        echo '<div class="film-card produit-item">';
                        echo '<img src="' . $photo . '" alt="' . $nom . '" class="produit-photo" onerror="this.onerror=null; this.src=\'../../assets/img/error.png\';">';

                        echo '<h5>' . $nom . '</h5>';
                        echo '<form action="reservation.php" method="get">
                    <button type="submit" class="btn btn-dark mt-2" name="destination" value="' . $nom . '">Voir le produit</button>
                  </form>';
                        echo '</div>';
                    }
                }
                ?>
                <h6 class="fw-bold">Pommes Bio <span class="text-danger float-end">2,80€</span></h6>
                <p class="text-muted small mb-1"><s>3,50€</s></p>
                <p class="text-muted small">1kg – Origine France</p>
                <div class="text-warning small mb-2">★★★★★ <span class="text-muted">(128)</span></div>
            </div>
            <div class="mt-auto">
                <button class="btn btn-danger w-100"><i class="bi bi-cart"></i> Ajouter au panier</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS + Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>
