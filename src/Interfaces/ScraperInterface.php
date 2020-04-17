<?php

namespace App\Interfaces;

interface ScraperInterface
{

    /**
     * @param string $url
     * @return bool
     */
    public function scrape(string $url): bool;

}
