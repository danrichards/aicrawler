<?php

namespace Dan\AiCrawler\Scrapers;

/**
 * Class ArticleScraper
 *
 * The purpose a Scraper is to score content in the AiCrawler DOM object. We
 * extend the AbstractScraper so there is no dependency on Laravel's config
 * pattern. Although this is a great way to supply rules for your scrapers.
 *
 * @package Dan\AiCrawler\Scrapers
 * @author Dan Richards <danrichardsri@gmail.com>
 */
class ArticleScraper extends AbstractScraper {

    /**
     * Items with rules in the config file.
     *
     * @var array $items
     */
    protected $items = ['headline', 'content', 'image', 'author'];

    /**
     * This is a single purpose scraper so provide a URL when initializing.
     *
     * @param AiCrawler|html|url $html
     */
    public function __construct($html) {
        parent::__construct($html);
    }

    /**
     *
     *
     * @param null $item
     */
    public function handle($item = null) {
        if (is_null($item)) {
            foreach ($this->items as $i) {
                if ($rules = config("aicrawler.heuristics.blog.{$i}")) {
                    $this->scrape($rules);
                }
            }
        } elseif ($rules = config("aicrawler.heuristics.blog.{$item}")) {
            $this->scrape($rules);
        }
    }

    public function response()
    {

    }

}