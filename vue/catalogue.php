<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body class="container py-4">

<!-- Bannière -->
<div class="banner text-dark">
    <h3><strong>Offres de la semaine</strong></h3>
    <p>Découvrez nos promotions exclusives jusqu'à -30%</p>
    <button class="btn btn-primary">Voir les offres</button>
</div>

<!-- Recherche + filtres -->
<div class="d-flex mb-4 gap-2">
    <input type="text" class="form-control" placeholder="Rechercher un produit...">
    <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
    <select class="form-select w-auto">
        <option>Trier par</option>
    </select>
    <button class="btn btn-outline-secondary"><i class="bi bi-funnel"></i> Filtres</button>
</div>

<!-- Catégories -->
<div class="mb-4">
    <button class="btn btn-secondary rounded-pill me-2">Tous les produits</button>
    <button class="btn btn-light rounded-pill me-2">Fruits & Légumes</button>
    <button class="btn btn-light rounded-pill me-2">Boulangerie</button>
    <button class="btn btn-light rounded-pill me-2">Produits laitiers</button>
    <button class="btn btn-light rounded-pill me-2">Viandes</button>
    <button class="btn btn-light rounded-pill me-2">Boissons</button>
    <button class="btn btn-light rounded-pill me-2">Épicerie</button>
</div>

<!-- Produits -->
<body class="bg-light">

<div class="container py-5">
    <div class="row g-4">
        <!-- Produit 1 -->
        <div class="col-md-3">
            <div class="product-card bg-warning bg-opacity-25 position-relative p-3">
                <span class="badge bg-danger text-white badge-custom">-20%</span>
                <i class="bi bi-eye-fill product-icon text-warning"></i>
                <div class="card-body text-center">
                    <h6> <strong>Bananes Bio</strong></h6>
                    <small class="text-muted">Origine: Équateur</small><br>
                    <span class="old-price">2,50€</span> <span class="price">1,99€</span><br>
                    <small>Le kg</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <!-- Produit 2 -->
        <div class="col-md-3">
            <div class="product-card bg-danger bg-opacity-10 position-relative p-3">
                <i class="bi bi-arrow-return-left product-icon text-danger"></i>
                <div class="card-body text-center">
                    <h6><strong>Pain de campagne</strong></h6>
                    <small class="text-muted">Boulangerie artisanale</small><br>
                    <span class="price">3,20€</span><br>
                    <small>400g</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <!-- Produit 3 -->
        <div class="col-md-3">
            <div class="product-card bg-primary bg-opacity-10 position-relative p-3">
                <span class="badge bg-success badge-custom">Nouveau</span>
                <i class="bi bi-box-fill product-icon text-primary"></i>
                <div class="card-body text-center">
                    <h6><strong>Yaourt nature</strong></h6>
                    <small class="text-muted">Ferme locale</small><br>
                    <span class="price">2,75€</span><br>
                    <small>Pack de 4</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <!-- Produit 4 -->
        <div class="col-md-3">
            <div class="product-card bg-success bg-opacity-10 position-relative p-3">
                <span class="badge bg-danger text-white badge-custom">-15%</span>
                <i class="bi bi-heart-fill product-icon text-success"></i>
                <div class="card-body text-center">
                    <h6><strong>Tomates cerises</strong></h6>
                    <small class="text-muted">Agriculture raisonnée</small><br>
                    <span class="old-price">3,50€</span> <span class="price">2,99€</span><br>
                    <small>Barquette 250g</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <!-- Produit 5 -->
        <div class="col-md-3">
            <div class="product-card bg-purple bg-opacity-10 position-relative p-3">
                <i class="bi bi-people-fill product-icon text-purple"></i>
                <div class="card-body text-center">
                    <h6><strong>Filet de poulet</strong></h6>
                    <small class="text-muted">Élevage français</small><br>
                    <span class="price">8,90€</span><br>
                    <small>500g</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <!-- Produit 6 -->
        <div class="col-md-3">
            <div class="product-card bg-warning bg-opacity-10 position-relative p-3">
                <i class="bi bi-info-circle-fill product-icon text-warning"></i>
                <div class="card-body text-center">
                    <h6><strong>Jus d’orange</strong></h6>
                    <small class="text-muted">Pressé à froid</small><br>
                    <span class="price">3,45€</span><br>
                    <small>1L</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <!-- Produit 7 -->
        <div class="col-md-3">
            <div class="product-card bg-secondary bg-opacity-10 position-relative p-3">
                <span class="badge bg-success badge-custom">Bio</span>
                <i class="bi bi-plus-circle-fill product-icon text-secondary"></i>
                <div class="card-body text-center">
                    <h6><strong>Riz complet</strong></h6>
                    <small class="text-muted">Agriculture biologique</small><br>
                    <span class="price">2,80€</span><br>
                    <small>500g</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>

        <!-- Produit 8 -->
        <div class="col-md-3">
            <div class="product-card bg-danger bg-opacity-10 position-relative p-3">
                <span class="badge bg-primary badge-custom">Lot</span>
                <i class="bi bi-arrow-left-right product-icon text-danger"></i>
                <div class="card-body text-center">
                    <h6><strong>Chocolat noir</strong></h6>
                    <small class="text-muted">70% cacao</small><br>
                    <span class="price">4,50€</span><br>
                    <small>Lot de 3 tablettes</small><br>
                    <button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#">«</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">…</a></li>
            <li class="page-item"><a class="page-link" href="#">8</a></li>
            <li class="page-item"><a class="page-link" href="#">»</a></li>
        </ul>
    </nav>

</div>

</body>
</html>
