<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;

class OfferController extends AbstractController
{
    /**
     * Lecture d'une offre d'emploi
     * 
     * @param   int     $id     Identifiant de l'offre
     * 
     * @return Response
     */

     #[Route('/offer-{id}', name: 'offre-{title}')]

    public function index(OfferRepository $offerRepository, int $id): Response
    {
        // On récupère l'offre qui correspond à l'id passé dans l'url
        $offer = $offerRepository(Offer::class)->findBy(['id' => $id]);


        return $this->render('offer/index.html.twig', [
            'offer' => $offer,
        ]);
    }

    /**
     * Création / Modification d'un offre
     * 
     * @param   int     $id     Identifiant de l'offre
     * 
     * @return Response
     */

    #[Route('/offer_add', name: 'new')]
    #[Route('/offer_edit/{id}', name: 'edit')]
    public function edit(EntityManagerInterface $entityManager, OfferRepository $offerRepository, Offer $offer, Request $request, int $id=null): Response
    {

        // Si un identifiant est présent dans l'url alors il s'agit d'une modification
        // Dans le cas contraire il s'agit d'une création d'article
        if($id) {
            $mode = 'update';
            // On récupère l'offre qui correspond à l'id passé dans l'url
            $offer = $offerRepository->findBy(['id' => $id])[0];

        }
        else {
            $mode = 'new';
            $offer = new Offer();         
        }
        $form = $this->createForm(OfferType::class, $offer);
            $offer = $form->getData();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
        }


        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offer);
            $entityManager->flush();
            return $this->redirectToRoute('Home');
        }

        $parameters = array(
            'form'      => $form->createView(),
            'offer'     => $offer,
            'mode'      => $mode
        );

        return $this->render('offer/edit.html.twig', $parameters);
    }

    /**
     * Suppression d'une offre d'emploi
     * 
     * @param   int     $id     Identifiant de l'article
     * 
     * @return Response
     */
    #[Route('/offer_remove/{id}', name: 'remove')]

    public function remove(EntityManagerInterface $entityManager, OfferRepository $offerRepository, int $id): Response
    {
        // On récupère l'article qui correspond à l'id passé dans l'URL
        $offer = $offerRepository->findBy(['id' => $id])[0];

        // L'article est supprimé
        $entityManager->remove($offer);
        $entityManager->flush();

        return $this->redirectToRoute('Home');
    }

    /**
     * Compléter l'offre d'emploi avec des informations avant enregistrement
     * 
     * @param   Offer       $offer
     * @param   string      $mode 
     * 
     * @return Offer
     */


    /**
     * Enregistrer un offre en base de données
     * 
     * @param   Offer       $offer
     * @param   string      $mode 
     */
//    private function saveOffer(OfferRepository $entityManager, Offer $offer){
//
//       $entityManager->persist($offer);
//        $entityManager->flush();
//        $this->addFlash('success', 'Enregistré avec succès');
//    }

}