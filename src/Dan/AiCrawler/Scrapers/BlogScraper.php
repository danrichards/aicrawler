<?php namespace Dan\AiCrawler\Scrapers;

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
        parent::__construct($node, $config);
    }

    /**
     * Run Heuristics and generate the payload.
     *
     * @return $this
     */
    public function scrape() {
        $this->setHeuristics($this->blogHeuristics);
        return parent::scrape();
    }

}