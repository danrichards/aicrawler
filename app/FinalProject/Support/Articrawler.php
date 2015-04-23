<?php namespace FinalProject\Support;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class Articrawl
 * @package FinalProject
 * @author Dan Richards
 */
class Articrawler extends Crawler {

    /**
     * An array of what contexts this node should be considered for.
     *
     * e.g. ['content']
     *
     * @var array
     */
    protected $considerFor;

    /**
     * An associative array of associative arrays
     *
     * e.g.
     * [
     *      'headline' => [
     *
     *      ],
     *      'content' => [
     *      ],
     *      'image' => [
     *      ]
     * ]
     *
     * @var $scores
     */
    use ScoreTrait;

    /**
     * Same as above, except the inner array has extra data gathered from our Heuristics.
     *
     * @var $extra
     * @contains methods setExtra([$key|[assoc], $data) and getExtra($key|[$keys])
     */
    use ExtraTrait;

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
     * Count how many tags exists as children or some other classification relative to the node provided,
     * If no node is provided, use $this
     *
     * @param $node
     * @param $type
     * @param $filter
     * @param $children
     */
    public function getTagsCount($tags, $classification = 'children', $minWord = 0) {
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
        $set->each(function($n, $i) use ($tags, &$count, $minWord) {
            $name = $n->nodeName();
            if ($minWord <= $n->numWords()) {
                if (is_array($tags) && in_array($name, $tags))
                    $count++;
                elseif ($name == $tags)
                    $count++;
            }
        });

        return $count;
    }

    /**
     * Return an associative array of attribute key - values
     *
     * Similar to extract() but only for a single node.
     *
     * @param $attributes
     */
    public function getAttributes($attributes) {
        $attributes = (array) $attributes;
        $count = count($attributes);

        $data = array();
        foreach ($attributes as $a)
            $data[$a] = parent::attr($a);

        return $data;
    }

    /**
     * Get the number
     */
    public function getParagraphs() {
        return $this->getTagsCount("p");
    }

    /**
     * The number of sentences in the text.
     */
    public function numSentences() {
        $total = 0;
        $sentences = explode('.', rtrim($this->text(), '.'));
        foreach($sentences as $s) {
            $first = mb_substr($s, 0, 1, "UTF-8");
            $upper = mb_strtolower($first, "UTF-8") != $first;
            $total += $upper ? 1 : 0;
        }
        return $total;
    }

    /**
     * The number of words in the text.
     *
     * @return mixed
     */
    public function numWords() {
        return str_word_count(regex_remove_extraneous_whitespace($this->text()));
    }

    /**
     * The number of characters in the text
     */
    public function numCharacters() {
        return strlen(regex_remove_extraneous_whitespace($this->text()));
    }

}