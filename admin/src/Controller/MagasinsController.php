<?php

namespace App\Controller;

use App\Entity\LogActivite;
use App\Entity\Magasins;
use App\Entity\OffresEmplois;
use App\Form\MagasinsForm;
use App\Repository\MagasinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/magasins')]
final class MagasinsController extends AbstractController
{
    #[Route(name: 'app_magasins_index', methods: ['GET'])]
    public function index(MagasinsRepository $magasinsRepository): Response
    {
        return $this->render('magasins/dashboard.html.twig', [
            'magasins' => $magasinsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_magasins_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $magasin = new Magasins();
        $form = $this->createForm(MagasinsForm::class, $magasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($magasin);
            $entityManager->flush();
            //  Enregistrement dans le log
            $log = new LogActivite();
            $log->setAction("Ajout d'un magasin");
            $log->setDetail("Paristanbul ".$magasin->getVilleMagasin()); // ou autre info
            $log->setDate(new \DateTime());
            $log->setUtilisateur(null); // à remplacer plus tard par $this->getUser()

            $entityManager->persist($log);
            $entityManager->flush();

            return $this->redirectToRoute('app_magasins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('magasins/new.html.twig', [
            'magasin' => $magasin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_magasins_show', methods: ['GET'])]
    public function show(Magasins $magasin): Response
    {
        return $this->render('magasins/show.html.twig', [
            'magasin' => $magasin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_magasins_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Magasins $magasin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MagasinsForm::class, $magasin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_magasins_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('magasins/edit.html.twig', [
            'magasin' => $magasin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_magasins_delete', methods: ['POST'])]
    public function delete(Request $request, Magasins $magasin, EntityManagerInterface $entityManager,
     Magasins $magasins): Response
    {
        if ($this->isCsrfTokenValid('delete'.$magasin->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($magasin);
            $entityManager->flush();
            //  Enregistrement dans le log
            $log = new LogActivite();
            $log->setAction("Suppression d'un magasin");
            $log->setDetail("Paristanbul ".$magasins->getVilleMagasin()); // ou autre info
            $log->setDate(new \DateTime());
            $log->setUtilisateur(null); // à remplacer plus tard par $this->getUser()

            $entityManager->persist($log);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_magasins_index', [], Response::HTTP_SEE_OTHER);
    }
}
