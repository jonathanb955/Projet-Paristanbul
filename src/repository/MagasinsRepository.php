<?php

namespace repository;
require_once __DIR__ . '/../bdd/Bdd.php';

use bdd\Bdd;
use modele\Magasins;




class MagasinsRepository
{
    public function ajoutMagasin(Magasins $magasin) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('INSERT INTO magasins (	ville_magasin ,rue ,image,	cp, num_tel, horaire_ouverture, horaire_fermeture, jours_ouverture,	video_magasin
) VALUES (:ville_magasin, :rue, :image,:cp,:num_tel,:horaire_ouverture,:horaire_fermeture,:jours_ouverture,:video_magasin)');
        $req->execute([
            'id_magasin' => $magasin->getIdMagasin(),
           'ville_magasin'=>$magasin->getVillleMagasin(),
            'rue'=>$magasin->getRue(),
            'image'=>$magasin->getImage(),
            'cp'=>$magasin->getCp(),
            'num_tel'=>$magasin->getNumTel(),
            'horaire_ouverture'=>$magasin->getHoraireOuverture(),
            'horaire_fermeture'=>$magasin->getHoraireFermeture(),
            'jours_ouverture'=>$magasin->getJoursOuverture(),
            'video_magasin'=>$magasin->getVideoMagasin(),

        ]);
        return $magasin;
    }
    public function modifMagasin(Magasin $magasin) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('UPDATE magasin 
        SET ville_magasin=:ville_magasin, 
            rue=:rue,
             image= :image,
             cp=:cp,
             num_tel=:num_tel,
             horaire_ouverture=:horaire_ouverture,
             horaire_fermeture=:horaire_fermeture,
             jours_ouverture=:jours_ouverture,
             video_magasin=:video_magasin
        WHERE id_magasin = :id_magasin');

        $req->execute([
            'id_magasin' => $magasin->getIdMagasin(),
            'ville_magasin'=>$magasin->getVillleMagasin(),
            'rue'=>$magasin->getRue(),
            'image'=>$magasin->getImage(),
            'cp'=>$magasin->getCp(),
            'num_tel'=>$magasin->getNumTel(),
            'horaire_ouverture'=>$magasin->getHoraireOuverture(),
            'horaire_fermeture'=>$magasin->getHoraireFermeture(),
            'jours_ouverture'=>$magasin->getJoursOuverture(),
            'video_magasin'=>$magasin->getVideoMagasin()
        ]);

        return $magasin;
    }

}