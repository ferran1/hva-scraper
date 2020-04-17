<?php

namespace App\Command;

use App\Scraper\NewsScraper;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ScrapeNewsCommand extends Command
{

    const URL = "https://www.hva.nl/over-de-hva/nieuws-en-agenda/hva-nieuws/hva-nieuws.html?";

    protected static $defaultName = 'scrape:hvanews';

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var NewsScraper
     */
    private $scraper;

    public function __construct(NewsScraper $scraper, LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
        $this->scraper = $scraper;
    }

    protected function configure()
    {
        $this->setDescription('Start scraper for HvA news (https://www.hva.nl/over-de-hva/nieuws-en-agenda/hva-nieuws/hva-nieuws.html?)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->logger->info("Started scraping hva news");

        $this->scraper->scrape(self::URL);

        $io->success('Scrape went successful');

        return 0;
    }


}
