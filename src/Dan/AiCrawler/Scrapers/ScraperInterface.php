<?php namespace Dan\AiCrawler\Scrapers;

interface ScraperInterface {

    /**
     * Drop an nodes that that will jumble our data abstraction. Merge singular nodes with others were beneficial.
     *
     * @return $this
     */
    public function prep();

    /**
     * Run Heuristics and generate the payload.
     *
     * @return $this
     */
    public function scrape();

    /**
     * Optionally, run a Clojure on the payload and return the payload. Then run and sanitizers and return the payload.
     *
     * @return $this
     */
    public function choose();

    /**
     * A basic object that we use for an API response.
     *
     * @return \stdClass()
     */
    public function response();

}