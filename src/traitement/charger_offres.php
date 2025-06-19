<?php
$pdo = new PDO('mysql:host=localhost;dbname=bdd_paristanbul;charset=utf8', 'root', '');



    // Produits par catégorie via sous-catégorie
    $req = $pdo->prepare("
        SELECT  `secteur_activite`, `titre_poste`, `ville`, `departement`, `type_contrat`, `detail_poste` 
        FROM offre_emplois oe
       
    ");


if ($req->rowCount() > 0) {
    while ($offres = $req->fetch(PDO::FETCH_ASSOC)) {
        $secteur_activite = $offres['secteur_activite'];
        $titre_poste = $offres['titre_poste'];
        $ville = $offres['ville'];
        $departement = $offres['departement'];
        $type_contrat = $offres['type_contrat'];
        $detail_poste = $offres['detail_poste'];

        echo'        <div class="col-md-4">';
        echo'        <div class="card h-100 shadow-sm border-0">';
        echo' <div class="bg-danger text-white p-3">';
        echo'                    <span class="badge bg-light text-danger mb-2">'.$secteur_activite'.</span>';
        echo'                    <h6 class="fw-bold">'.$titre_poste.'</h6>';
        echo'                    <small>'.$ville($departement).' - '.$type_contrat.'</small>';
        echo'                </div>';
        echo'                <div class="card-body">';
        echo'                    <p class="small">'.$detail_poste.'</p>';
        echo'                    <div class="d-flex flex-wrap gap-2 mb-3">';
        echo'                        <span class="badge bg-light text-dark">Marketing</span>';
        echo'                        <span class="badge bg-light text-dark">Communication</span>';
        echo'                        <span class="badge bg-light text-dark">Digital</span>';
        echo'                    </div>';
        echo'                    <a href="#" class="btn btn-danger btn-sm w-100">Voir l’offre</a>';
        echo'                </div>';

        echo'           </div>';

        echo'        </div>';
    }
} else {
    echo '<div class="col-12"><p>Aucun produit trouvé dans cette catégorie.</p></div>';
}