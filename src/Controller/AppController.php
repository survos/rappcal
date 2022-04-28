<?php

namespace App\Controller;

use App\Service\ScrapeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/at_home', name: 'app_rapp_at_home')]
    public function at_home(ScrapeService $scrapeService): Response
    {
        $events = $scrapeService->getCsvEvents();
//        $events = $scrapeService->scrapeAtHomeEvents();

        return $this->render('at_home/index.html.twig', [
//            'public' => $scrapeService->scrapePublicEvents(),
            'private' => $scrapeService->getCsvEvents(),
        ]);
    }

}
