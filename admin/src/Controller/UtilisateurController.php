<?php

namespace App\Controller;

use App\Entity\LogActivite;
use App\Entity\Utilisateur;
use App\Form\UtilisateurForm;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{


    #[Route(name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }



    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurForm::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hasher = new NativePasswordHasher();
            $mdpHash = $hasher->hash($utilisateur->getMdp());
            $utilisateur->setMdp($mdpHash);

            $entityManager->persist($utilisateur);
            $entityManager->flush();
            //  Enregistrement dans le log
            $log = new LogActivite();
            $log->setAction("Ajout d'un utilisateur");
            $log->setDetail($utilisateur->getPrenom()." ".$utilisateur->getNom());
            $log->setDate(new \DateTime());
            $log->setUtilisateur(null); // à remplacer plus tard par $this->getUser()
            $entityManager->persist($log);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index');
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find($id);

        if (!$utilisateur) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurForm::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getIdUtilisateur(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
            //  Enregistrement dans le log
            $log = new LogActivite();
            $log->setAction("Suppression d'un utilisateur");
            $log->setDetail($utilisateur->getPrenom()." ".$utilisateur->getNom());
            $log->setDate(new \DateTime());
            $log->setUtilisateur(null); // à remplacer plus tard par $this->getUser()
            $entityManager->persist($log);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
