<?php

namespace App\Scraper;

use App\Interfaces\ScraperInterface;
use App\Service\MailService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\BrowserKit\HttpBrowser;

class DLONewsScraper implements ScraperInterface
{

    const URL = "https://nieuws-mededelingen.mijnhva.nl/paginas/default.aspx?Show=Announcements";
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var HttpClient
     */
    private $client;

    public function __construct(LoggerInterface $logger, MailService $mailService)
    {
        $this->logger = $logger;
        $this->mailService = $mailService;
        $this->client = HttpClient::create();
    }

    public function scrape(string $loginUrl): bool
    {
        $this->submitLoginForm($loginUrl);
        return true;
    }

    // Submit login form
    private function submitLoginForm($loginUrl){
//        $response = $this->client->request('GET', $loginUrl);
//        $html = $response->getContent();
//        $crawler = new Crawler($html);

        $browser = new HttpBrowser(HttpClient::create());
//        dd($loginUrl);
        $crawler = $browser->request('GET', $loginUrl);
//        $crawler = $browser->request('GET', 'https://github.com/login');

        // Select form
//        $form = $crawler->selectButton('Sign in')->form();

        $form = $crawler->selectButton('Login')->form();
//        dd($form);

//        $form = $crawler->selectButton('#submitButton')->form();


    }

}
