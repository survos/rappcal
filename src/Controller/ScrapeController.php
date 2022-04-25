<?php

namespace App\Controller;

use App\DTO\Story;
use App\Entity\UrlCache;
use App\Repository\EventRepository;
use App\Service\CalendarService;
use Goutte\Client;
use Spatie\IcalendarGenerator\Components\Calendar;
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
    public function index(): Response
    {
        return $this->render('foothills/index.html.twig', [
            'articles' => $this->scrapeArticles(),
            'controller_name' => 'FoothillsController',
        ]);
    }

    #[Route('/at_home', name: 'app_rapp_at_home')]
    public function at_home(): Response
    {
        return $this->render('athome/index.html.twig', [
            'events' =>  $this->calendarService->loadCsv()
        ]);
    }


    // ideally the ISC feed.

    private function loadEvents() {
        $this->calendarService->loadCsv();
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


    private function scrapeArticles()
    {

        $url = 'https://www.rappnews.com/news/foothills/';
        $html = $this->cache->get(md5($url), function(ItemInterface $item) use ($url) {
            $item->expiresAfter(60 * 60 * 24);
            // actually do the fetch
            return $this->httpClient->request('GET', $url)->getContent();
//        $response  = $this->httpClient('GET', $url);
        });
        $crawler = new Crawler($html, $url);

        $articles = [];

        $x = $crawler->filter('.card-body a.tnt-asset-link')->each(function(Crawler $node) use ($articles) {
//            dd($node, $node->outerHtml(), $node->links()[0]);
            $base = 'https://www.rappnews.com';
            return new Story(headline: $node->innerText(), url: $base . $node->attr('href'));
        });
        return $x;
        dd($x);
        return $articles;

        $crawler->filter('.card-body a.tnt-asset-link')->each(function(Crawler $node) {
           dd($node->innerText(), $node->outerHtml());
        });
        return $html;

    }

}
