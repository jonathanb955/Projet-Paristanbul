<?php

namespace App\Controller;

use App\Entity\LogActivite;
use App\Entity\OffresEmplois;
use App\Form\OffresEmplois1Form;
use App\Form\OffresEmploisForm;
use App\Repository\OffresEmploisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/offres/emplois')]
final class OffresEmploisController extends AbstractController
{
    #[Route(name: 'app_offres_emplois_index', methods: ['GET'])]
    public function index(OffresEmploisRepository $offresEmploisRepository): Response
    {
        return $this->render('offres_emplois/index.html.twig', [
            'offres_emplois' => $offresEmploisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_offres_emplois_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offresEmploi = new OffresEmplois();
        $form = $this->createForm(OffresEmplois1Form::class, $offresEmploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offresEmploi);
            $entityManager->flush();
            //  Enregistrement dans le log
            $log = new LogActivite();
            $log->setAction("Ajout d'une offre");
            $log->setDetail($offresEmploi->getTitrePoste());
            $log->setDate(new \DateTime());
            $log->setUtilisateur(null); // à remplacer plus tard par $this->getUser()
            $entityManager->persist($log);
            $entityManager->flush();

            return $this->redirectToRoute('app_offres_emplois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offres_emplois/new.html.twig', [
            'offres_emploi' => $offresEmploi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offres_emplois_show', methods: ['GET'])]
    public function show(OffresEmplois $offresEmploi): Response
    {
        return $this->render('offres_emplois/show.html.twig', [
            'offres_emploi' => $offresEmploi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offres_emplois_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OffresEmplois $offresEmploi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffresEmploisForm::class, $offresEmploi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_offres_emplois_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('offres_emplois/edit.html.twig', [
            'offres_emploi' => $offresEmploi,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_offres_emplois_delete', methods: ['POST'])]
    public function delete(Request $request, OffresEmplois $offresEmploi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offresEmploi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offresEmploi);
            $entityManager->flush();
            //  Enregistrement dans le log
            $log = new LogActivite();
            $log->setAction("Suppression d'une offre");
            $log->setDetail($offresEmploi->getTitrePoste());
            $log->setDate(new \DateTime());
            $log->setUtilisateur(null); // à remplacer plus tard par $this->getUser()
            $entityManager->persist($log);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_offres_emplois_index', [
            'message' => "L'offre a bien été supprimée.",
            'type' => 'success'
        ]);
    }



}
