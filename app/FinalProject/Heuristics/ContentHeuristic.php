<?php namespace FinalProject\Heuristics;

use FinalProject\Support\Articrawler;
use FinalProject\Support\Considerations;

/**
 * Score nodes based on their likeliness to be the node that wraps all the content.
 *
 * This heuristic counts relevant paragraph elements and reviews attributes to pass judgement.
 *
 * @todo 2nd Content Heuristic
 * Make a second content heuristic (a backup), that calculates the level (breadth) that has the highest mean average
 * of words within a more expansive group of relevant nodes (children), p, blockquote, h2, h3, div
 *
 * @todo 3rd Content Heuristic
 * Consider marrying singular nodes with their parents. In the case where every paragraph is isolated within it's own
 * wrapper (an anti-pattern, but not unlikely), this would enabled them to be view as children.
 *
 * @package FinalProject\Heuristics
 */
class ContentHeuristic implements HeuristicInterface {

    private static $lexicalPenalty = 0.5;
    private static $recurrencePenalty = 0.75;

    private static $wrappers = ['section', 'div', 'article'];
    private static $byNoMeansAWrapper = [
        'a', 'abbr', 'applet', 'area', 'audio', 'base', 'br', 'button', 'canvas', 'caption', 'cite', 'col',
        'colgroup', 'datalist', 'dd', 'del', 'dfn', 'dialog', 'dir', 'dl', 'dt', 'em', 'embed', 'footer', 'h1', 'h2',
        'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hr', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label',
        'legend', 'li', 'link', 'map', 'menu', 'menuitem', 'meta', 'meter', 'nav', 'object', 'ol', 'optgroup', 'option',
        'param', 'progress', 's', 'samp', 'script', 'select', 'source', 'style', 'sub', 'sup', 'table',
        'tbody', 'textarea', 'tfoot', 'tr', 'track', 'tt', 'ul', 'var', 'video', 'wbr'
    ];
    private static $paragraphs = ['p', 'blockquote', 'h2', 'h3'];
    private static $attributes = ['id', 'class', 'name', 'alt', 'title', 'value', 'label'];
    private static $attributeBonus = ['content', 'body', 'article', 'entry', 'postarea', 'story', 'book', 'post'];
    private static $attributeAncestorsToCheck = 2;

    private static $pMinWordCount = 5;
    private static $pWeightOne = 0.3;
    private static $pWeightThree = 0.3;
    private static $pWeightSix = 0.2;
    private static $wrapperWeight = 0.1;
    private static $attributeWeight = 0.1;

    /**
     * Run the Heuristic. Return a node to consider or false.
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     * @return bool|Articrawler
     */
    public static function run(Articrawler &$node, Considerations $considerations) {
        /**
         * If we reach
         */
        if (is_null($node) || $node === false) {
            print "invalid node!";
        } else {
            if (!in_array($node->nodeName(), static::$byNoMeansAWrapper))
                return static::wrapped($node, $considerations);
        }

        return null;
    }

    /**
     * Content within a conventional wrapper scoring heuristic
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     * @return $this
     */
    private function wrapped(Articrawler &$node, Considerations $considerations) {
        $node->setConsiderFor("content");
        $score = 0;

        /**
         * Conventional wrappers get a bonus
         */
        $wrapperScore = in_array($node->nodeName(), static::$wrappers) ? static::$wrapperWeight : 0;
        $node->setScore("content", "wrapper", $wrapperScore);

        /**
         * Score region based on how many paragraphs it contains (with minimum word count or higher)
         */
        $pScore = 0;
        if ($pCount = $node->getTagsCount(static::$paragraphs, "children", static::$pMinWordCount)) {
            $pScore += ($pCount >= 1) ? static::$pWeightOne : 0;
            $pScore += ($pCount >= 3) ? static::$pWeightThree : 0;
            $pScore += ($pCount >= 6) ? static::$pWeightSix : 0;
            $node->setExtra("paragraphs", $pCount);
        }
        $node->setScore("content", "p", $pScore);

        /**
         * Score attributes (include parent and grandparents)
         */
        $node->setScore("content", "attribute", static::attributeBonus($node));


        return ($node->getScoreTotal("content") > 0) ? $node : null;
    }

    /**
     * Determine if an attribute bonus is available for a node or one of its ancestors.
     *
     * @param Articrawler $node
     * @return float
     */
    public function attributeBonus(Articrawler &$node) {
        /**
         * Check current node's attributes
         */
        $attr = $node->getAttributes(static::$attributes);
        foreach($attr as $a => $value) {
            if (!is_null($value) && regex_set_contains_substr($value, static::$attributeBonus)) {
                $node->setExtra("attribute", $value);
                return static::$attributeWeight;
            }
        }

        /**
         * Also check ancestors for the attribute bonus
         */
//        $parents = $node->parents();
//        $counter = 0;
//        while ($counter < static::$attributeAncestorsToCheck && $counter < $parents->count()) {
//            $attr = $parents[$counter]->getAttributes(static::$attributes);
//            foreach($attr as $a => $value) {
//                if (!is_null($value) && regex_set_contains_substr($value, static::$attributeBonus))
//                    return static::$attributeWeight;
//            }
//            $counter ++;
//        }

        return 0;
    }
}