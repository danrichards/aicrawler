<?php namespace Dan\AiCrawler\Scrapers;

use Dan\AiCrawler\Heuristics\HeadlineHeuristic;
use Dan\AiCrawler\Heuristics\ContentHeuristic;
use Dan\AiCrawler\Heuristics\ClosestImageHeuristic;
use Dan\AiCrawler\Heuristics\DateHeuristic;
use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;

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
     */
    function __construct(AiCrawler $node = null, $config = []) {
        $this->config($config)->setHtml($node);
        /**
         * Run our searches if we've been given a $node
         */
        if (!is_null($this->html) && count($this->blogHeuristics))
            $this->scrape();
    }

    /**
     * In case you really want to cut down on the boiler-plate.
     *
     * @param $config
     */
    public function config($config) {
        foreach ($config as $property => $value)
            $this->{$property} = $value;
        return $this;
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

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     */
    public function setHtml(AiCrawler $html)
    {
        $this->html = $html;
        return $this;
    }
}