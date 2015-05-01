<?php namespace AiCrawler\Scrapers;

use AiCrawler\Heuristics\HeadlineHeuristic;
use AiCrawler\Heuristics\ContentHeuristic;
use AiCrawler\Heuristics\ClosestImageHeuristic;
use AiCrawler\Heuristics\DateHeuristic;
use AiCrawler\Support\AiCrawler;
use AiCrawler\Support\Considerations;

/**
 * Class Scraper
 * @package AiCrawler
 * @author Dan Richards
 */
class BlogScraper extends AbstractScraper implements ScraperInterface {

    /**
     * Collections for our considerations
     */
//    protected $considerations;

    /**
     * Default Configuration
     */
    function __construct(AiCrawler $node = null, $config = []) {
        $this->config($config)->setHtml($node);

        /**
         * Run our searches if we've been given a $node
         */
        if (!is_null($this->html)) {
            $this->scrape();
//            $memBeforeHeadline = memory_get_usage();
//            $this->searchHeadline($node);
//            $this->headline()->sortByScore("headline");
//            $this->searchContent($node);
//            $this->content()->sortByScore("content");
//            $memAfterHeadline = memory_get_usage();
            // print "memory used: ".number_format($memAfterHeadline).", headlines: ".number_format($memAfterHeadline-$memBeforeHeadline)."\n";
        }
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
        $this->setHeuristics([
            "headline" => "HeadlineHeuristic",
            "content" => "ContentHeuristic",
            "image" => "ClosestImageHeuristic"
        ]);
        parent::scrape();
        return $this;
    }

    /**
     * Use BFS to gather all of our considerations into an array of AiCrawler nodes.
     *
     * @param $node AiCrawler
     * @param $considerations array Necessary for our recursion. Also the base case.
     */
//    public function searchHeadline(AiCrawler &$node) {
//        /**
//         * Run the Heuristic, add to Considerations if scoring. This saves us from running BFS again later
//         * to review our scores.
//         */
//        if ($headline = HeadlineHeuristic::score($node, $this->headline()))
//            $this->headline()->push($headline);
//
//        /**
//         * Later on, $considerations will be loaded into a Collection and sorted by score, but for now, the order of
//         * occurrence matters.
//         */
//        $node->children()->each(function ($n, $i) {
//            $this->searchHeadline($n);
//        });
//    }

    /**
     * Grade all the content and build a list of considerations
     */
//    public function searchContent(AiCrawler &$node) {
//        /**
//         * Run the Heuristic, add to Considerations if scoring. This saves us from running BFS again later
//         * to review our scores.
//         */
//        $content = ContentHeuristic::score($node, $this->content());
//        if ($content)
//            $this->content()->push($content);
//
//        /**
//         * Later on, $considerations will be loaded into a Collection and sorted by score, but for now, the order of
//         * occurrence matters.
//         */
//        $node->children()->each(function ($n, $i) {
//            if ($n)
//                $this->searchContent($n);
//        });
//    }

    /**
     * Last change to examine all the considerations that were scored and return one.
     *
     * @param Considerations $c
     * @return mixed|null
     */
//    public static function choose(Considerations $c) {
//
//    }

//    public function searchImage() {
//
//    }
//
//    public function headline() {
//        return $this->headline;
//    }
//
//    public function content() {
//        return $this->content;
//    }
//
//    public function image() {
//        return $this->image;
//    }

    /**
     * @return $this
     */
//    public function reset() {
//        $this->children = true;
//        $this->considerations = ['headline' => [], 'content' => [], 'image' => []];
//        $this->headline = new Considerations();
//        $this->content = new Considerations();
//        $this->image = new Considerations();
//        return $this;
//    }

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