<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');
$categorie = $_GET['categorie'] ?? null;
$search = $_GET['search'] ?? null;

if (!empty($search)) {
    $req = $pdo->prepare("SELECT nom_produit, photo FROM produits WHERE nom_produit LIKE :search");
    $req->execute(['search' => '%' . $search . '%']);
} elseif ($categorie !== null && $categorie != 0) {
    // Produits par catégorie via sous-catégorie
    $req = $pdo->prepare("
        SELECT p.nom_produit, p.photo 
        FROM produits p
        JOIN sous_categories s ON p.ref_sous_categorie = s.id_sous_categorie
        WHERE s.ref_categorie = :cat
    ");
    $req->execute(['cat' => $categorie]);
} else {
    // Tous les produits
    $req = $pdo->query("SELECT nom_produit, photo FROM produits");
}

if ($req->rowCount() > 0) {
    while ($produit = $req->fetch(PDO::FETCH_ASSOC)) {
        $nom = htmlspecialchars($produit['nom_produit']);
        $photo = htmlspecialchars($produit['photo']);
        echo '<div class="col-md-3">';
        echo ' <div class="product-card bg-warning bg-opacity-25 position-relative p-3">';
        echo '  <span class="badge bg-danger text-wAhite badge-custom">-20%</span>';
        echo ' <i class="bi bi-eye-fill product-icon text-warning"></i>';
        echo ' <div class="card-body text-center">';
        echo '<img src="' . $photo . '" alt="' . $nom . '" class="produit-photo" onerror="this.onerror=null; this.src=\'../../assets/img/error.png\';">';
        echo '  <h6><strong>' . $nom . '</strong></h6>';
        echo '<small class="text-muted">Origine ??</small><br>';
        echo '<span class="old-price">Prix ??</span> <span class="price"></span><br>';
        echo '<small>Le kg ??</small><br>';
        echo '<button class="btn btn-primary mt-2 add-btn"><i class="bi bi-cart"></i> Ajouter au panier</button>';
        echo ' </div></div></div>';
    }
} else {
    echo '<div class="col-12"><p>Aucun produit trouvé dans cette catégorie.</p></div>';
}