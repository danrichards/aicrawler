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
 * @todo First element not a p tag
 * Many blogs lead the content area with a figure, caption, image or heading element. Consider booking this node ahead of the
 * searchImage() method and how it can be left out of the text.
 * @example http://arstechnica.com/information-technology/2015/04/20/as-moores-law-turns-50-what-does-the-future-hold-for-the-transistor/
 * @example http://www.cnn.com/2015/04/15/living/high-profits-breckenridge-cannabis-club-intro/index.html
 * @example http://www.huffingtonpost.com/2015/04/19/leaked-game-of-thrones-hbo_n_7096000.html
 * @example http://www.pcmag.com/slideshow/story/326584/12-things-you-didn-t-know-your-chromecast-could-do
 * @example http://www.theguardian.com/us-news/2015/apr/19/california-drought-protest-bottled-water
 *
 * @todo Dump all script / noscript nodes
 * @example http://www.huffingtonpost.com/2015/04/19/leaked-game-of-thrones-hbo_n_7096000.html
 *
 * @todo Should the span element be added to paragraphs?
 * @example http://www.reuters.com/article/2015/04/19/us-election-graham-idUSKBN0NA13J20150419
 *
 * @todo Should accomodations be made for the query string?
 * @example http://techcrunch.com/2015/04/18/google-wants-to-speed-up-the-web-with-its-quic-protocol/?ncid=rss&cps=gravity_1462_-8975113281583662707#.nfni5u:c1SG
 * @example http://techcrunch.com/2015/04/18/google-wants-to-speed-up-the-web-with-its-quic-protocol/
 *
 * @package FinalProject\Heuristics
 */
class ContentHeuristic implements HeuristicInterface {

    /**
     * Penalties are given by multiplying.
     *
     * @var float $penalty
     */
    private static $recurrencePenalty = 0.75;
    private static $attrPenalty = 0.1;

    private static $wrappers = ['section', 'div', 'article'];
    private static $byNoMeansAWrapper = [
        'a', 'abbr', 'applet', 'area', 'audio', 'base', 'br', 'button', 'canvas', 'caption', 'cite', 'col',
        'colgroup', 'datalist', 'dd', 'del', 'dfn', 'dialog', 'dir', 'dl', 'dt', 'em', 'embed', 'footer', 'h1', 'h2',
        'h3', 'h4', 'h5', 'h6', 'head', 'header', 'hr', 'iframe', 'img', 'input', 'ins', 'kbd', 'keygen', 'label',
        'legend', 'li', 'link', 'map', 'menu', 'menuitem', 'meta', 'meter', 'nav', 'object', 'ol', 'optgroup', 'option',
        'param', 'progress', 's', 'samp', 'script', 'select', 'source', 'style', 'sub', 'sup', 'table',
        'tbody', 'textarea', 'tfoot', 'tr', 'track', 'tt', 'ul', 'var', 'video', 'wbr'
    ];
    private static $byNoMeansAWrapperAttr = ['header', 'footer', 'nav'];

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
            /**
             * Eliminate nodes that are by no means a wrapper.
             */
            if (self::omitByElement($node)) {
                $eliminate = self::omitByAttr($node);
                if (!$eliminate)
                    return self::wrapped($node, $considerations);
            }
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
        $wrapperScore = in_array($node->nodeName(), self::$wrappers) ? self::$wrapperWeight : 0;
        $node->setScore("content", "wrapper", $wrapperScore);

        /**
         * Score region based on how many paragraphs it contains (with minimum word count or higher)
         */
        $pScore = 0;
        if ($pCount = $node->getTagsCount(self::$paragraphs, "children", self::$pMinWordCount)) {
            $pScore += ($pCount >= 1) ? self::$pWeightOne : 0;
            $pScore += ($pCount >= 3) ? self::$pWeightThree : 0;
            $pScore += ($pCount >= 6) ? self::$pWeightSix : 0;
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
        $attr = $node->getAttributes(self::$attributes);
        foreach($attr as $a => $value) {
            if (!is_null($value) && regex_set_contains_substr($value, self::$attributeBonus)) {
                $node->setExtra("attribute", $value);
                return self::$attributeWeight;
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

    /**
     * @param Articrawler $node
     * @return bool
     */
    private static function omitByAttr(Articrawler &$node)
    {
        $eliminate = false;
        $attr = $node->getAttributes(self::$attributes);
        foreach ($attr as $a => $value) {
            if (!is_null($value) && regex_set_contains_substr($value, self::$byNoMeansAWrapperAttr))
                $eliminate = true;
        }
        return $eliminate;
    }

    /**
     * @param Articrawler $node
     * @return bool
     */
    private static function omitByElement(Articrawler &$node)
    {
        return !in_array($node->nodeName(), self::$byNoMeansAWrapper);
    }
}