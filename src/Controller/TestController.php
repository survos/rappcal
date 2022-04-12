<?php

namespace App\Controller;

use App\Service\CalendarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function test_ics(CalendarService $calendarService): Response
    {
        $icsContent  = $calendarService->fetchUrl('https://www.officeholidays.com/ics/usa/arizona');
        $calendar = $calendarService->parseIcs($icsContent);
        dd($calendar->getCalendar());

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
