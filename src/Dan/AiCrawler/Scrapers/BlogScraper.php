<?php namespace Dan\AiCrawler\Scrapers;

use Dan\AiCrawler\Heuristics\DateHeuristic;
use Dan\AiCrawler\Support\AiConfig;
use Dan\AiCrawler\Support\AiCrawler;

/**
 * Class Scraper
 * @package AiCrawler
 * @author Dan Richards
 */
class BlogScraper extends AbstractScraper implements ScraperInterface {

    protected $blogHeuristics = [
        "headline" => "HeadlineHeuristic",
        "content" => "ContentHeuristic",
        "image" => "ClosestImageHeuristic"
    ];

    /**
     * Default Configuration
     *
     * @param AiCrawler|html|url $node
     * @param array $config
     */
    function __construct($node = null, $config = []) {
        $this->config = new AiConfig($config);
        $this->setHtml($node);
        if (!is_null($this->html))
            $this->scrape();
    }

    /**
     * Run Heuristics and generate the payload.
     *
     * @return $this
     */
    public function scrape() {
        $this->setHeuristics($this->blogHeuristics);
        parent::scrape();
        return $this;
    }
}