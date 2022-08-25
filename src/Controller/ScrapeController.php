<?php

namespace App\Controller;

use App\DTO\Story;
use App\Entity\UrlCache;
use App\Repository\EventRepository;
use App\Repository\StoryRepository;
use App\Service\CalendarService;
use App\Service\ScrapeService;
use Goutte\Client;
use Spatie\IcalendarGenerator\Components\Calendar;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScrapeController extends AbstractController
{
    public function __construct(private CacheInterface $cache,
                                private CalendarService $calendarService,
                                private HttpClientInterface $httpClient) {
    }

    #[Route('/foothills', name: 'app_foothills')]
    public function index(StoryRepository $storyRepository): Response
    {
        $newsArticles = [];
        $articles = $storyRepository->findBy(['isDoer' => false],[],80);
        foreach ($articles as $article) {
            if ($doer = $article->getDoerName()) {
                //
            } else {
                array_push($newsArticles, $article);
            }
        }

        return $this->render('foothills/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/scrape_foothills', name: 'app_scrape_foothills')]
    public function scrape(ScrapeService $scrapeService): Response
    {
        $articles = $scrapeService->scrapeFoothillsArticles();
        // https://www.rappnews.com/news/foothills/

        return $this->render('foothills/index.html.twig', [
            'articles' => $articles,
            'controller_name' => 'FoothillsController',
        ]);
    }

    private function scrapeEvents() {
        // ideally scrape https://rappathome.net/content.aspx?page_id=4004&club_id=442822&actr=3 after login.

        $url = 'https://rappathome.net/content.aspx?page_id=4001&club_id=442822';
        $html = $this->cache->get(md5($url), function(ItemInterface $item) use ($url) {
            $item->expiresAfter(60 * 60 * 24);
            // actually do the fetch
            return $this->httpClient->request('GET', $url)->getContent();
//        $response  = $this->httpClient('GET', $url);
        });
        $crawler = new Crawler($html, $url);
    }


}
