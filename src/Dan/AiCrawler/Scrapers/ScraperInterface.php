<?php namespace Dan\AiCrawler\Scrapers;

use Dan\AiCrawler\Support\AiCrawler;

interface ScraperInterface {

    /**
     * Drop an nodes that that will jumble our data abstraction. Merge singular nodes with others were beneficial.
     *
     * @param AiCrawler $top
     * @return AiCrawler
     */
    public function prep();

    /**
     * Run Heuristics and generate the payload.
     */
    public function scrape();

    /**
     * Optionally, run a Clojure on the payload and return the payload. Then run and sanitizers and return the payload.
     *
     * @return mixed
     */
    public function choose();

}