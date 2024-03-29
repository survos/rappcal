<?php

namespace App\Service;

use IcsReader\IcsReader;
use League\Csv\Reader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CalendarService
{
    public function __construct(
        private CacheInterface $cache,
        private HttpClientInterface $client,
        private ParameterBagInterface $bag) {

    }
    public function parseIcs($icsContent)
    {
        $reader = new IcsReader();
        return $reader->parse($icsContent);

        print_r($ics->getCalendar());
        print_r($ics->getEvents());

    }

    public function fetchUrl(string $url): string {

    $content = $this->cache->get($key = md5($url), function(ItemInterface $item) use ($url) {
        $item->expiresAfter(3600);
        return $this->client->request('GET', $url)->getContent();
        });
        return $content;
    }
}
