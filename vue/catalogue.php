<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Catalogue Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/catalogue.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="container py-4">

<!-- Bannière -->
<div class="banner text-dark mb-4">
    <h3><strong>Offres de la semaine</strong></h3>
    <p>Découvrez nos promotions exclusives jusqu'à -30%</p>
    <button class="btn btn-primary">Voir les offres</button>
</div>

<!-- Filtres -->
<div class="d-flex mb-4 gap-2">
    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
    <button class="btn btn-outline-secondary" onclick="searchProduct()"><i class="bi bi-search"></i></button>
</div>

<!-- Catégories dynamiques -->
<div id="categories" class="mb-4">
    <button class="btn btn-secondary rounded-pill me-2" data-categorie="0">Tous les produits</button>
    <?php
    $pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');
    $cats = $pdo->query("SELECT id_categorie, nom_categorie FROM categories ORDER BY id_categorie");
    foreach ($cats as $cat) {
        echo '<button class="btn btn-light rounded-pill me-2" data-categorie="' . $cat['id_categorie'] . '">' . htmlspecialchars($cat['nom_categorie']) . '</button>';
    }
    ?>
</div>

<!-- Contenu produits -->
<div id="produits" class="row g-4"></div>

<!-- Script JS -->
<script>
    const categories = document.getElementById("categories");
    const produitsContainer = document.getElementById("produits");

    categories.addEventListener("click", (e) => {
        if (e.target.tagName === "BUTTON") {
            [...categories.children].forEach(btn => btn.classList.replace("btn-secondary", "btn-light"));
            e.target.classList.replace("btn-light", "btn-secondary");

            const cat = e.target.getAttribute("data-categorie");

            fetch(`../../traitement/charger_produits.php?categorie=${cat}`)
                .then(res => res.text())
                .then(html => produitsContainer.innerHTML = html)
                .catch(() => {
                    produitsContainer.innerHTML = "<p>Erreur de chargement des produits.</p>";
                });
        }
    });

    function searchProduct() {
        const search = document.getElementById("searchInput").value;
        fetch(`../../traitement/charger_produits.php?search=${encodeURIComponent(search)}`)
            .then(res => res.text())
            .then(html => produitsContainer.innerHTML = html)
            .catch(() => {
                produitsContainer.innerHTML = "<p>Erreur de recherche.</p>";
            });
    }

    window.addEventListener("DOMContentLoaded", () => {
        const defaultBtn = document.querySelector('[data-categorie="0"]');
        if (defaultBtn) defaultBtn.click();
    });
</script>

<!-- Styles -->
<style>
    .card-body {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        text-align: center;
    }
    .card-body:hover {
        transform: scale(1.05);
    }
    .card-body img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
    .badge-custom {
        position: absolute;
        top: 10px;
        right: 10px;
    }
</style>

</body>
</html>
