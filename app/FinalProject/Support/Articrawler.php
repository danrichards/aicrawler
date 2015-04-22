<?php
namespace FinalProject\Support;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Articrawl
 * @package FinalProject
 * @author Dan Richards
 */
class Articrawler extends Crawler {

    protected $considerFor;

    protected $headlineScore;
    protected $contentScore;
    protected $imageScore;

    /**
     * Call the parent constructor and get some additional stuff ready
     *
     * @param null $node
     * @param null $currentUri
     * @param null $baseHref
     */
    function __construct($node = null, $currentUri = null, $baseHref = null) {
        parent::__construct($node, $currentUri, $baseHref);
        $this->considerFor = [];
    }

    /**
     * Count how many tags exists as children or some other classification relative to the node provided,
     * If no node is provided, use $this
     *
     * @param $node
     * @param $type
     * @param $filter
     * @param $children
     */
    public function countTags($tags, $classification = 'children') {
        $count = 0;

        /**
         * Define our set with the provided classification
         */
        switch ($classification) {
            case "siblings":
                $set = $this->siblings();
                break;
            case "parents":
                $set = $this->parents();
                break;
            case "filter":
                $set = (is_array($tags)) ? $this->filter(implode(" ", $tags)) : $this->filter($tags);
                break;
            default:
                $set = $this->children();
        }

        /**
         * Count our tags
         */
        $set->each(function($n, $i) use ($tags, &$count) {
            $name = $n->nodeName();
            if (is_array($tags) && in_array($name, $tags))
                $count ++;
            elseif($name == $tags)
                $count++;
        });

        return $count;
    }

    /**
     * Test this later, it may occur automatically
     *
     * @return string
     */
//    public function nodeName() {
//        return strtolower(parent::nodeName());
//    }

    /**
     * Flag the node as an consideration for a particular search
     *
     * @param $search
     * @return $this
     */
    public function setConsiderFor($searchContext) {
        if (!in_array($searchContext, $this->considerFor))
            $this->considerFor[] = $searchContext;
        return $this;
    }

    /**
     * True if this node is a consideration for a given search context
     *
     * @param $search
     * @return bool
     */
    public function isConsiderableFor($searchContext) {
        return in_array($searchContext, $this->considerFor);
    }

    /**
     * Get the Score for one of our contexts
     *
     * @return double
     */
    public function getScore($which)
    {
        switch ($which) {
            case "headline":
                return $this->headlineScore;
            case "content":
                return $this->contentScore;
            case "image":
                return $this->imageScore;
        }
        return 0;
    }

    /**
     * Set the Score for one of our contexts
     *
     * @param mixed $score
     */
    public function setScore($which, $score)
    {
        // -1 <= score <= 1
        if ($score < -1)
            $score = -1;
        elseif ($score > 1)
            $score = 1;

        switch ($which) {
            case "headline":
                $this->headlineScore = $score;
                break;
            case "content":
                $this->contentScore = $score;
                break;
            case "image":
                $this->imageScore = $score;
                break;
        }
        return $this;
    }

}