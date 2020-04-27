<?php

namespace App\Command;

use App\Scraper\DLONewsScraper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScrapeDLONewsCommand extends Command
{

    const URL = "https://nieuws-mededelingen.mijnhva.nl/paginas/default.aspx?Show=Announcements";
    const LOGIN_URL = "https://dlo.mijnhva.nl/";

    protected static $defaultName = 'scrape:dlonews';

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var DLONewsScraper
     */
    private $scraper;

    public function __construct(DLONewsScraper $scraper, LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
        $this->scraper = $scraper;
    }

    protected function configure()
    {
        $this->setDescription('Start scraper for DLO news (https://www.hva.nl/over-de-hva/nieuws-en-agenda/hva-nieuws/hva-nieuws.html?)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->logger->info("Started scraping HvA DLO news");

        $this->scraper->scrape(self::LOGIN_URL);

        $io->success('Scrape went successful');

        return 0;
    }


}
