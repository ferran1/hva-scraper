<?php

namespace App\Scraper;

use App\Interfaces\ScraperInterface;
use App\Service\MailService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class NewsAndNoticesScraper
 * @package App\Scraper
 * Scrapes news from HvA.nl
 */
class NewsScraper implements ScraperInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(LoggerInterface $logger, MailService $mailService)
    {
        $this->logger = $logger;
        $this->mailService = $mailService;
    }

    /**
     * @inheritDoc
     */
    public function scrape(string $url): bool
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        $html = $response->getContent();
        $crawler = new Crawler($html);

        $properties = $crawler->filter('.media-body')->each(function (Crawler $property, $i) {

            $heading = $property->filter('.media-heading')->text();

            $detailUrl = $property->filter('a')->attr('href');
            $detailUrl = "https://hva.nl" . $detailUrl;
            $introParagraph = $this->getIntroFromDetailsPage($detailUrl);

            $dateTime = $this->getDateTimeFromDetailsPage($detailUrl);
            $element = explode("|", $dateTime);
            $dateTime = $element[0];

            $propertyDate = $this->convertStringToDateTime($dateTime);

            $currentDate = date('Y-m-d H:i:s');
            $currentDate = new \DateTime($currentDate);

            // Calc the difference in hours between element datetime and current datetime
            $interval = $propertyDate->diff($currentDate);
            $difference_in_hours = ($interval->days * 24) + $interval->h;

            if ($difference_in_hours <= 24) {
                return $element = [
                    'heading' => $heading,
                    'introParagraph' => $introParagraph,
                    'detailUrl' => $detailUrl
                ];
            }
        });

        foreach ($properties as $property) {
            $this->mailService->sendMail(['HvA news: ' . $property['heading'],
                $property['introParagraph'] . " " . $property['detailUrl']]);
            sleep(5000);
        }

        return true;
    }

    private function getIntroFromDetailsPage($detailUrl): string
    {
        $client = HttpClient::create();
        $htmlDetail = $client->request('GET', $detailUrl)->getContent();
        $crawler = new Crawler($htmlDetail);

        return $crawler->filter('.lead')->text();
    }

    private function getDateTimeFromDetailsPage($detailUrl): string
    {
        $client = HttpClient::create();
        $htmlDetail = $client->request('GET', $detailUrl)->getContent();
        $crawler = new Crawler($htmlDetail);

        return $crawler->filter('small')->text();
    }

    private function convertStringToDateTime($date): \DateTime
    {
        $stringPieces = explode(" ", $date);

        $day = $stringPieces[0];
        $month = $stringPieces[1];

        $month = $this->getMonthAsNumber($month);

        $year = $stringPieces[2];
        $time = $stringPieces[3];

        try {
            $date = new \DateTime($year . "/" . $month . "/" . $day . $time);
        } catch (\Exception $e) {
            echo $e;
        }

        return $date;
    }

    private function getMonthAsNumber(string $month): int
    {
        switch ($month) {
            case "jan":
                return 1;
            case "feb":
                return 2;
            case "mrt":
                return 3;
            case "apr":
                return 4;
            case "mei":
                return 5;
            case "jun":
                return 6;
            case "jul":
                return 7;
            case "aug":
                return 8;
            case "sep":
                return 9;
            case "okt":
                return 10;
            case "nov":
                return 11;
            case "dec":
                return 12;
        }
    }
}
