<?php

namespace App\Service;

use App\Entity\Story;
use App\Repository\StoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use League\Csv\Reader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ScrapeService
{

    public function __construct(private ParameterBagInterface $bag,
                                private HttpClientInterface $httpClient,
                                private StoryRepository $storyRepository,
                                private EntityManagerInterface $entityManager,
                                private CacheInterface $cache) {

    }


    private function getPage(string $url): string {
        $html = $this->cache->get(md5($url), function(ItemInterface $item) use ($url) {
//            $item->expiresAfter(60 * 60 * 24);
            // actually do the fetch
            return $this->httpClient->request('GET', $url)->getContent();
//        $response  = $this->httpClient('GET', $url);
        });


        return $html;

    }

    private function webpageToStory($url): Story {

        if (!$story = $this->storyRepository->findOneBy(['url' => $url])) {
            $html = $this->getPage($url);
            $story = (new Story())
                ->setHtml($html)
                ->setUrl($url);
            $this->entityManager->persist($story);
        };

        $crawler = new Crawler($story->getHtml());


//        $headline = $crawler->filterXPath('//h1/span')->first()->innerText();
        $headline = $crawler->filterXPath(' //*[@property="og:title"]')->attr('content');
        $author = $crawler->filterXPath(' //meta [@name="author"]')->attr('content');
        if (!str_contains($author, 'Foothill')) {
            $this->entityManager->remove($story);
            return $story;
        }
        $story->setIsDoer(str_contains($headline, 'Doer'));
        if ($story->getIsDoer()) {
            $story->setDoerName($headline);
        }
        $story->setPublicationDate(new \DateTime($crawler->filterXPath(' //time')->attr('datetime')));
        $story->setAuthor($author);
        $story->setHeadline($headline);
        $story->setDescription($crawler->filterXPath(' //*[@property="og:description"]')->attr('content'));

//        <time datetime="2022-02-18T09:30:00-05:00"

        //*[//meta[@property="og:url"]/@content

//        <meta property="og:url" content="https://www.rappnews.com/foothills_forum/young-and-youngish-in-rappahannock/article_b6f2a3a2-9064-11ec-90b5-7f3bf894a1a8.html" />

//        $creditNodes = $crawler->filterXPath(' //*[@itemprop="author"]');
//        $creditNodes->each(function(Crawler $node) {
//            dump($node->innerText());
//        });
//        dd($creditNodes);
//
////        $author = $crawler->filterXPath('//')->first()->innerText();
//dd('x');

//        <div class="meta">
//        <span>
//            <ul class="list-inline">
//        <li><span itemprop="author" class="tnt-byline">By Bob Hurley for Foothills Forum</span></li>


        try {
            $description  = $crawler->filterXPath(' //*[@itemprop="description"]')->first()->attr('content');
            $story->setDescription(strip_tags($description));
        } catch (\Exception $exception) {

        }

        $imageNodes = $crawler->filterXPath(' //*[@itemprop="image"]');
        if (is_array($imageNodes)) {
            dd($imageNodes);
        } else {
            $imageNode = $imageNodes->first();
            if (!$imageNode->count()) {
                return $story; //
                dd($imageNodes, $imageNode);
            }
            assert($imageNode->count(), "Missing imageNode");
//            dump($imageNode->outerHtml());
        }


        try {
            $imageNode = $imageNodes->first();
        } catch (\Exception $exception) {
            dd($imageNodes, $imageNodes->outerHtml(), $exception);
        }
        //*[@itemprop="homeLocation"]/meta[@itemprop="name"]/@content
//        dump($headline, $imageNode->outerHtml());

        $urlNodes = $imageNode->evaluate($xPath = '//meta[@itemprop="url"]');
            if (is_array($urlNodes)) {
                dd($urlNodes, $url, is_array($imageNode) ? $imageNode : $imageNode->outerHtml());
            } else {
                // it's a crawler, not a simple type
                $urlNode = $urlNodes->first();
            }

        try {
        } catch (\Exception $exception) {
            dd($urlNodes, $urlNodes->outerHtml(), $exception);
        }
        $imageUrl = $urlNode->attr('content');
            $story->setImageUrl($imageUrl);
//        dd($urlNode, $content);
//        assert($urlNode->count(), "Missing " . $xPath . " in " . $imageNode->outerHtml());
//        try {
//            dd($urlNode->outerHtml(), $urlNode->innerText());
//        } catch (\Exception $exception) {
//            dd($imageNode, $urlNode, $imageNode->outerHtml(), $exception);
//        }
//
//
//        $headline = $crawler->filter('body h1 span')->first()->innerText();
//        $image = $crawler->filter('div [itemprop="image"]')->first();
//
//        // hack
//        $meta = $image->filter('meta [itemprop="url"]')->first();
//        dd($meta);
//        dd($meta->nodeName());
//        dd($meta, $meta->outerHtml(), $meta->innerText());
//        dd($image, $image->outerHtml());
//
//
//
//
//        dd($headline, $meta, $meta->outerHtml(), $image, $url);

        return $story;

    }

    public function scrapeFoothillsArticles()
    {

        $url = 'https://www.rappnews.com/news/foothills/';
        $html = $this->cache->get(md5($url), function(ItemInterface $item) use ($url) {
//            dd('fetching ' . $url);
//            $item->expiresAfter(60 * 60 * 24);
            // actually do the fetch
            return $this->httpClient->request('GET', $url)->getContent();
//        $response  = $this->httpClient('GET', $url);
        });
        $crawler = new Crawler($html, $url);

        $articles = [];

        $x = $crawler->filter('.card-body a.tnt-asset-link')->each(function(Crawler $node) use ($articles) {
            $base = 'https://www.rappnews.com';
            $url = $base . $node->attr('href');
            $story = $this->webpageToStory($url);
            return $story;
        });
        $this->entityManager->flush();

        return $x;
        return $articles;

        $crawler->filter('.card-body a.tnt-asset-link')->each(function(Crawler $node) {
            dd($node->innerText(), $node->outerHtml());
        });
        return $html;

    }


    public function scrapePublicEvents() {
        // get public events, or get the downloaded events.

        $url = 'https://rappathome.net/content.aspx?page_id=4001&club_id=442822&action=cira&vm=MonthView';
//        $url = 'https://rappathome.net/content.aspx?page_id=4001&club_id=442822';
        $html = $this->cache->get(md5($url), function(ItemInterface $item) use ($url) {
            $item->expiresAfter(60 * 60 * 24);
            // actually do the fetch
            return $this->httpClient->request('GET', $url)->getContent();
//        $response  = $this->httpClient('GET', $url);
        });
        $crawler = new Crawler($html, $url);
        $crawler->filter('.list-event-container')->each( function(Crawler $node) {
            if (preg_match('/event_id=(\d+)/', $node->outerHtml(), $m)) {
                //
                $this->fetchEvent($m[1]);
                dd($m);
            }
            dd($node->outerHtml());
        });
        dd($html);

    }

    private function fetchEvent($id) {

        $url = "https://rappathome.net/events2/event_vcalendar.aspx?event_id=1216495";
//        $url = 'https://rappathome.net/content.aspx?page_id=4002&club_id=442822&item_id=1681592&event_date_id=255';
//        $url = 'https://rappathome.net/events2/event_vcalendar.aspx?event_id=' . $id;
        $response = $this->httpClient->request('GET', $url);
        $content = $response->getContent();
        dd($response->getHeaders(), $response, $content, $response->getInfo());


        $html = $this->cache->get(md5($url), function(ItemInterface $item) use ($url) {
            $item->expiresAfter(60 * 60 * 24);
            // actually do the fetch

            $response = $this->httpClient->request('GET', $url);
            dd($response->getHeaders());
            $content = $response->getContent();
//        $response  = $this->httpClient('GET', $url);
        });
        dd($html);
    }


    public function getCsvEvents()
    {
        //load the CSV document from a file path
        $csv = Reader::createFromPath('/home/tac/Downloads/event_information_data.csv', 'r');
        $csv->setHeaderOffset(0);

        $header = $csv->getHeader(); //returns the CSV header record
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object
        return $records;
        foreach ($records as $record) {
            dd($record);
        }
        dd($header, $records);

    }

    public function scrapeAtHomeEvents()
    {

        // Go to the symfony.com website
        $client = new Client(HttpClient::create(['timeout' => 60]));
        $formData = [
            'ctl00$ctl00$login_name' => $this->bag->get('at_home_username'),
            'ctl00$ctl00$password' => $this->bag->get('at_home_password')];

        $pageCrawler = $client->request('GET', $home = 'https://rappathome.net/');
//        $response = $client->getResponse();

        $crawler = $client->click($pageCrawler->selectLink('Friend Login')->link());
        $formElement = $crawler->filter('#form');
        $form = $formElement->form($formData);

        $crawler = $client
            ->submit($form, $formData);

        $response = $client->getResponse();
        dd($response->getHeaders(), $client->getCookieJar(), $client->getMaxRedirects(), $response, $client->getHistory(), $client->getCrawler());

        dump($client->getResponse()->headers);

        dd($form->getUri(), $form->getMethod(), $form->all(), $crawler);


        dump($crawler);
        dd($client, $client->getCookieJar(), $client->getResponse());

        // in theory, logged in, so now get the control panel.
        $page = $client->request('GET', $home);
        dd($client->getCookieJar(), $page);



        dump($response);
        dd($response, $form);

        dd($pageCrawler);



        $response  = $httpClient->request('GET', 'https://rappathome.net/content.aspx?page_id=31&club_id=442822&action=login&su=1&sc=1');
        dd($response->getContent(), $response->getHeaders(), $response->getStatusCode(), $response->getInfo());

        $response  = $httpClient->request('POST', 'https://rappathome.net/content.aspx?page_id=31&club_id=442822&action=login&su=1&sc=1',
            [
                'body' => $formData
            ]
        );
        dd($response->getContent(), $response->getHeaders(), $response->getStatusCode(), $response->getInfo());


        dump($client->getCookieJar());

        $loginKey = md5(json_encode($formData));
        $loggedInPage = $this->cache->get($loginKey, function (ItemInterface $item) use ($formData) {
        });

        $url = 'https://github.com';
        $response = $httpClient->request('GET', $url);

        dd($response->getContent());

        $client->getResponse();


//            $crawler = $client->request('GET', 'https://rappathome.net/content.aspx?page_id=31&club_id=442822&action=login');
//            $form = $crawler->selectButton('ctl00_ctl00_cancel_button')->form();
//            dd($form);
//
//            $crawler = $client->click($crawler->selectLink('Sign in')->link());
//            $form = $crawler->selectButton('Sign in')->form();
//            $crawler = $client->submit($form, ['login' => 'fabpot', 'password' => 'xxxxxx']);
//            $crawler->filter('.flash-error')->each(function ($node) {
//                print $node->text()."\n";
//            });

            $crawler = $client->request('POST', 'https://rappathome.net/content.aspx?page_id=31&club_id=442822&action=login&su=1&sc=1', $formData);
dump($client->getCookieJar());

        $pageCrawler = $client->request('GET', 'https://rappathome.net/content.aspx?page_id=15&club_id=442822');
        dd($pageCrawler->links());


            dump($crawler);
            dump($crawler->getNode(0));
            $html = $crawler->outerHtml();
            return $html;
        dd($loggedInPage);


        $crawler = $client->click($crawler->selectLink('Sign in')->link());
        $form = $crawler->selectButton('Sign in')->form();
        $crawler = $client->submit($form, );


        $crawler->filter('.flash-error')->each(function ($node) {
            print $node->text()."\n";
            dd($node);
        });

    }

}
