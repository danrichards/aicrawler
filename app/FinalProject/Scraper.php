<?php
namespace FinalProject;

use FinalProject\Support\Articrawler;

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
     * One search may benefit from the results of another search.
     * e.g. A image can be found more accurately if we know the depth of the content node we chose.
     *
     * An associative array (by context) that houses are nodes to consider.
     *
     * [
     *  'content' => array(Articrawler $nodes)
     *  'headline' => array(Articrawler $nodes)
     *  'image' => array(Articrawler $nodes)
     * ]
     *
     * @var $considerations
     */
    protected $considerations;

    /**
     * Default Configuration
     */
    function __construct(Articrawler $node = null, $config = null) {
        $this->reset()->config($config)->setHtml($node);

        /**
         * Run our searches if we've been given a $node
         */
        if (!is_null($this->html)) {
//            var_dump($node);
            $this->considerations['headline'] = $this->searchHeadline($this->html);
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
     * Use BFS to grade all the the objects
     *
     * @param $node Articrawler
     */
    public function searchHeadline(Articrawler &$node, $considerations = []) {
        $name = $node->nodeName();
        $text = $node->text();
        /**
         * Essentially, our heuristic (compartmentalize later)
         */
        if ($name == "h1") {
            $node->setConsiderFor("headline");
            /**
             * Subsequent h1 tags are penalized
             */
            if (count($considerations)) {
                $last = $considerations[count($considerations) - 1]->getScore("headline");
                $node->setScore("headline", $last * 0.75);
            } else {
                /**
                 * lexicalPenalty is enforced if
                 */
                $node->setScore("headline", 1 - lexicalPenalty($text, 0.5, 5, 1));
            }
            $considerations[] = $node;
//            print "Headline as follows: " . $text;
        } elseif ($name == "title") {
            $node->setConsiderFor("headline");
            $node->setScore("headline", 1);
            $considerations[] = $node;
//            print "Headline as follows: " . $text;
        } else {
            $node->setScore("headline", -1);
        }
        /**
         * END headline Heuristic
         */

        $node->children()->each(function ($n, $i) use (&$considerations) {
            $considerations = array_merge($considerations, $this->searchHeadline($n));
        });
        return $considerations;
    }

    /**
     * Grade all the content and build a list of considerations
     */
    public function searchContent() {

        // loop through nodes
        // loop through Heuristics (running them on each node)
        // pass the Score object to heuristic, return Score object, continuing to "keep score"
        // end loop with the ContentMetaScoreHeuristic (analyze the individual grades)
        // if the ContentMetaScore meets minimum criteria, add node to array of sorted considerations
    }

    public function searchImage() {

    }

    /**
     * Iterate through the considerations and return the highest scoring
     *
     * @param $considerations
     */
    public function highestScoring($considerations, $context) {

    }

    public function headline($index = null) {
        return ($index < count($this->considerations['headline'])) ? $this->considerations['headline'][$index] : false;
    }

    public function content($index = null) {
        return ($index < count($this->considerations['content'])) ? $this->considerations['content'][$index] : false;
    }

    public function image($index = null) {
        return ($index < count($this->considerations['image'])) ? $this->considerations['image'][$index] : false;
    }

    /**
     * Return an array of all the links in a node
     */
    public function links($domain = "", $hint = "") {

    }

    public function reset() {
        $this->children = true;
        $this->considerations = ['headline' => [], 'content' => [], 'image' => []];
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