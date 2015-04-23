<?php
namespace FinalProject;

use FinalProject\Heuristics\ContentHeuristic;
use FinalProject\Heuristics\HeadlineHeuristic;
use FinalProject\Support\Articrawler;
use FinalProject\Support\Considerations;
use Illuminate\Support\Facades\Config;

/**
 * Class Scraper
 * @package FinalProject
 * @author Dan Richards
 */
class Scraper {

    /**
     * Our master html node
     */
    protected $html;

    /**
     * Collections for our considerations
     */
    protected $considerations;

    protected $content;
    protected $image;
    protected $headline;

    protected $headlineHeuristic;

    /**
     * Default Configuration
     */
    function __construct(Articrawler $node = null, $config = null) {
        $this->reset()->config($config)->setHtml($node);

        /**
         * Run our searches if we've been given a $node
         */
        if (!is_null($this->html)) {
            $memBeforeHeadline = memory_get_usage();
            $this->searchHeadline($node);
            $this->headline()->sortByScore("headline");
            $this->searchContent($node);
            $this->content()->sortByScore("content");
            $memAfterHeadline = memory_get_usage();
            // print "memory used: ".number_format($memAfterHeadline).", headlines: ".number_format($memAfterHeadline-$memBeforeHeadline)."\n";
        }
    }

    /**
     * Setup some configuration before running links, content, header, or image
     */
    public function config($configArray = null) {
        if (!is_null($configArray)) {
            foreach ($configArray as $item => $value)
                $this->{$item} = $value;
        }
        return $this;
    }

    /**
     * Use BFS to gather all of our considerations into an array of Articrawler nodes.
     *
     * @param $node Articrawler
     * @param $considerations array Necessary for our recursion. Also the base case.
     */
    public function searchHeadline(Articrawler &$node) {
        /**
         * Run the Heuristic, add to Considerations if scoring. This saves us from running BFS again later
         * to review our scores.
         */
        if ($headline = HeadlineHeuristic::run($node, $this->headline()))
            $this->headline()->push($headline);

        /**
         * Later on, $considerations will be loaded into a Collection and sorted by score, but for now, the order of
         * occurrence matters.
         */
        $node->children()->each(function ($n, $i) {
            $this->searchHeadline($n);
        });
    }

    /**
     * Grade all the content and build a list of considerations
     */
    public function searchContent(Articrawler &$node) {
        /**
         * Run the Heuristic, add to Considerations if scoring. This saves us from running BFS again later
         * to review our scores.
         */
        if ($content = ContentHeuristic::run($node, $this->content()))
            $this->content()->push($content);

        /**
         * Later on, $considerations will be loaded into a Collection and sorted by score, but for now, the order of
         * occurrence matters.
         */
        $node->children()->each(function ($n, $i) {
            $this->searchContent($n);
        });
    }

    public function searchImage() {

    }

    public function headline() {
        return $this->headline;
    }

    public function content() {
        return $this->content;
    }

    public function image($index = null) {
        return $this->image;
    }

    /**
     * Return an array of all the links in a node
     */
    public function links($domain = "", $hint = "") {

    }

    public function reset() {
        $this->children = true;
        $this->considerations = ['headline' => [], 'content' => [], 'image' => []];
        $this->headline = new Considerations();
        $this->content = new Considerations();
        $this->image = new Considerations();
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
    public function setHtml(Articrawler $html)
    {
        $this->html = $html;
        return $this;
    }
}