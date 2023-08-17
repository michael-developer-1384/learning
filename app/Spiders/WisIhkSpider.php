<?php

//php artisan roach:run WisIhkSpider

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use RoachPHP\Http\Request;


class WisIhkSpider extends BasicSpider
{
    /** @return Request[] */
    protected function initialRequests(): array
    {
        return [
            new Request(
                'GET',
                'https://wis.ihk.de/kurse',
                [$this, 'parseOverview']
            ),
        ];
    }

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    public function parseOverview(Response $response): \Generator {

        \Log::info('parseOverview called');

        $pages = $response->filter('h3 > a.kurs-link')->links();

        foreach ($pages as $page) {
            // Alle Artikel-Seiten werden von der `parse`-Methode des Spiders verarbeitet.
            yield $this->request('GET', $page->getUri());
            
        }
    }

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): \Generator {

        \Log::info('parse called');
        $title = $response->filter('h1.fs-1')->text();

        /* $provider = $response->filter('h2.fs-lg a')->text();
        $address = $response->filter('h2.fs-lg')->text();
        $address = str_replace($provider, '', $address);*/
        $description = $response->filter('.pre-block.shift-h6')->text();
    
        //$price = $response->filter('.badge-soft-warning')->text();
    
        /*$formats = $response->filter('.badge')->each(function ($node) {
            return $node->text();
        }); 
        
        $contactName = $response->filter('.contact-item')->eq(0)->text();
        $contactEmail = $response->filter('.contact-item a[href^="mailto:"]')->text();
        $contactPhone = $response->filter('.contact-item')->eq(2)->text();
        $contactWebsite = $response->filter('.contact-item a[href^="http"]').text(); 
    
        $venue = $response->filter('.mb-4.pb-lg-2 b')->text(); */
    
        yield $this->item([
            'title' => $title,
            /* 'provider' => $provider,
            'address' => $address,*/
            'description' => $description,
            //'price' => $price,
            /*'formats' => $formats,
            'contact' => [
                'name' => $contactName,
                'email' => $contactEmail,
                'phone' => $contactPhone,
                'website' => $contactWebsite
            ], 
            'venue' => $venue */
        ]);
    }   
}
