<?php

namespace App\Controller;

use App\Entity\Magasins;
use App\Entity\OffresEmplois;
use App\Entity\Utilisateur;
use App\Repository\CandidaturesRepository;
use App\Repository\LogActiviteRepository;
use App\Repository\MagasinsRepository;
use App\Repository\OffresEmploisRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexAdminController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(UtilisateurRepository $utilisateurRepository , MagasinsRepository $magasinsRepository,
    OffresEmploisRepository $offresEmploisRepository , CandidaturesRepository $candidaturesRepository,
                              LogActiviteRepository $repository): Response
    {
        $nb_user = $utilisateurRepository->compteUtilisateur();
        $nb_magasins = $magasinsRepository->compteMagasins();
        $nb_offres = $offresEmploisRepository->compteOffres();
        $nb_candidatures = $candidaturesRepository->compteCandidatures();
        $logs = $repository->findBy([], ['date' => 'DESC']);




        return $this->render('index/dashboard.html.twig', [
            'nb_utilisateurs' => $nb_user,
            'nb_magasins'=>$nb_magasins,
            'nb_offres'=>$nb_offres,
            'nb_candidatures'=>$nb_candidatures,
            'logs'=>$logs
        ]);
    }

    #[Route('/utilisateurs', name: 'utilisateurs')]
    public function utilisateurs(EntityManagerInterface $em): Response
    {
        $utilisateurs = $em->getRepository(Utilisateur::class)->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
    #[Route('/magasins', name: 'magasins')]
    public function magasins(EntityManagerInterface $em): Response
    {
        $magasins = $em ->getRepository(Magasins::class)->findAll();
        return $this->render('magasins/index.html.twig',[
        'magasins' => $magasins,
        ]);
    }

    #[Route('/offres', name: 'offres')]
    public function offres(EntityManagerInterface $em): Response
    {
        $offres_emplois = $em ->getRepository(OffresEmplois::class)->findAll();

        return $this->render('offres_emplois/index.html.twig',[
            'offres_emplois' => $offres_emplois,
            ]);
    }

    #[Route('/parametres', name: 'parametres')]
    public function parametres(): Response
    {
        return $this->render('admin/parametres.html.twig');
    }
}
