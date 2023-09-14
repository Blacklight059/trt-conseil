<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\OfferRepository;

class HomeController extends AbstractController
{

    /**
         * Page d'accueil
         * 
         * @return Response
     */

    #[Route('/', name: 'Home')]
    public function index(OfferRepository $offerRepository): Response
    {
        
        //Toutes les offres d'emploi
        $offers = $offerRepository->findAll();

        return $this->render('/home/index.html.twig', [
            'offers' => $offers,
        ]);
    }
}
