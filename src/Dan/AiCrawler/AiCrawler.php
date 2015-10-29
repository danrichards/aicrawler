<?php

namespace Dan\AiCrawler;

use Symfony\Component\DomCrawler\Crawler as SymfonyCrawler;
use Dan\Core\Support\Traits\Extra;

/**
 * Class AiCrawler
 *
 * @package AiCrawler
 * @author Dan Richards <danrichardsri@gmail.com>
 */
class AiCrawler extends SymfonyCrawler {

    /**
     * Essentially, an associative array of associative arrays.
     *
     * e.g.
     * [
     *      'headline' => [
     *          'datapoint' => 'value'
     *      ]
     * ]
     *
     * @var $scores
     */
    use Scoreable;

    /**
     * Same as above, except the inner array has extra data gathered from our Heuristics.
     *
     * @var $extra
     * @contains methods setExtra([$key|[assoc], $data) and getExtra($key|[$keys])
     */
    use Extra;

    /**
     * Call the parent constructor and get some additional stuff ready
     *
     * @param null $node
     * @param null $currentUri
     * @param null $baseHref
     */
    function __construct($node = null, $currentUri = null, $baseHref = null) {
        parent::__construct($node, $currentUri, $baseHref);
    }

    /**
     * Count how many tags exists as children or some other classification relative to the node provided,
     * If no node is provided, use $this
     *
     * @param $tags
     * @param string $classification
     * @param int $minWord
     * @return int
     */
    public function getNumberOfRelatedTagsWithWordCount($tags, $classification = 'children', $minWord = 0) {
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
                if (is_array($tags) && in_array($name, $tags)) {
                    $count++;
                } elseif ($name == $tags) {
                    $count++;
                }
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
     * @return array
     */
    public function getAttributes($attributes) {
        $attributes = (array) $attributes;

        $data = [];
        foreach ($attributes as $a) {
            $data[$a] = parent::attr($a);
        }

        return $data;
    }

    /**
     * Get the number
     */
    public function numParagraphs() {
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
        return str_word_count(RegEx::removeExtraneousWhitespace($this->text()));
    }

    /**
     * The number of characters in the text
     */
    public function numCharacters() {
        return strlen(RegEx::removeExtraneousWhitespace($this->text()));
    }

}