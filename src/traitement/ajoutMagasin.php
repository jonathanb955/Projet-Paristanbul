<?php

use modele\Magasins;
use repository\MagasinsRepository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once "../modele/Magasins.php";
require_once "../repository/MagasinsRepository.php";

if (!empty($_POST["ville_magasin"]) && !empty($_POST["rue"]) && !empty($_POST["image"])&& !empty($_POST["cp"] )
    && !empty($_POST["num_tel"]) && !empty($_POST["horaire_ouverture"]) && !empty($_POST["horaire_fermeture"])
    && !empty($_POST["jours_ouverture"]) && !empty($_POST["video_magasin"]) ){

    $nouveauMagasin = new Magasins([
        'ville_magasin'=> $_POST ["ville_magasin"],
        'rue'=>$_POST ["rue"],
        'image'=>$_POST ["image"],
        'cp'=> $_POST ["cp"],
        'num_tel'=>  $_POST ["num_tel"],
        'horaire_ouverture'=> $_POST ["horaire_ouverture"],
    'horaire_fermeture'=> $_POST ["horaire_fermeture"],
        'jours_ouverture'=> $_POST ["jours_ouverture"],
        'video_magasin'=> $_POST ["video_magasin"],
    ]);

    $MagasinsRepository = new MagasinsRepository();

    try {
        $MagasinsRepository->ajoutMagasin($nouveauMagasin);
        header("Location: ../../vue/ajoutMagasin.php");
        exit;
    } catch (Exception $e) {
        die('Erreur lors de l\'insertion en BDD : ' . $e->getMessage());
    }

} else {
    header("Location: ../../vue/ajoutMagasin.php?error=champs_vides");
    exit;
}
?>
