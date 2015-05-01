<?php namespace AiCrawler\Heuristics;

use AiCrawler\Support\AiCrawler;
use AiCrawler\Support\Considerations;

class HeadlineHeuristic extends AbstractHeuristic {

    private static $lexicalPenalty = 0.5;
    private static $recurrencePenalty = 0.9;

    private static $titleWeight = 0.75;
    private static $h1Weight = 0.75;
    private static $h1TitleBonusWeight = 0.25;
    private static $h1MinWords = 2;
    private static $h1MinCharacters = 6;

    /**
     * Run the Heuristic. Return a node to consider.
     *
     * @param AiCrawler $node
     * @param Considerations $considerations
     * @return AiCrawler
     */
    public static function score(AiCrawler &$node, Considerations $considerations) {
        /**
         * This heuristic handles each element differently.
         */
        switch ($node->nodeName()) {
            case "h1":
                $result = self::h1($node, $considerations);
                break;
            case "title":
                $result = self::title($node, $considerations);
                break;
            default:
                $result = null;
        }

        return $result;
    }

    /**
     * Last change to examine all the considerations that were scored and return one.
     *
     * @overrides parent::choose()
     * @param Considerations $c
     * @return mixed|null
     */
    public static function choose(Considerations $c) {
        return $c->sortByScore("headline")->first();
    }

    /**
     * h1 scoring heuristic
     *
     * @param AiCrawler $node
     * @param Considerations $considerations
     * @return $this
     */
    private static function h1(AiCrawler &$h1, Considerations $considerations) {
        $h1->setConsiderFor("headline");
        $score = 0;

        /**
         * Subsequent h1 tags are penalized
         */
        if ($considerations->getTagsCount("h1")) {
            // Get the last occurrence
            $last = $considerations->filter(function ($n) {
                return $n->nodeName() == "h1";
            });

            if (count($last)) {
                $last = $last->last();
                /**
                 * If the last occurrence was lexically penalized, don't apply a recurrence penalty
                 */
                if (lexicalPenalty($last->text(), true, self::$h1MinCharacters, self::$h1MinWords))
                    $score += self::$h1Weight - lexicalPenalty($h1->text(), self::$lexicalPenalty, self::$h1MinCharacters, self::$h1MinWords);
                else
                    $score += $last->getScoreTotal("headline") * self::$recurrencePenalty;
            } else {
                $score += $last->getScoreTotal("headline") * self::$recurrencePenalty;
            }
        /**
         * The first h1 occurrence is only subject to the lexicalPenalty
         */
        } else {
            $score += self::$h1Weight - lexicalPenalty($h1->text(), self::$lexicalPenalty, self::$h1MinCharacters, self::$h1MinWords);
        }

        /**
         * Examine the similarity between h1 and title, if more than half the words match, apply a bonus. In most cases
         * the check for this bonus will occur on every h1 element because the title element always appears before
         * first.
         *
         * This bonus was created because some blogs use h1 elements everywhere.
         * @example http://arstechnica.com/information-technology/2015/04/20/as-moores-law-turns-50-what-does-the-future-hold-for-the-transistor/
         */
        $title = $considerations->filter(function ($n) {
            return $n->nodeName() == "title";
        });
        if (count($title)) {
            $titleArray = explode(" ", regex_remove_extraneous_whitespace($title->first()->text()));
            $h1Array = explode(" ", regex_remove_extraneous_whitespace($h1->text()));
            $halfSizeOfLargest = count($titleArray) > count($h1Array) ? count($titleArray) / 2 : count($h1Array) / 2;
            $sizeOfIntersection = count(array_intersect($titleArray, $h1Array));
            $score += $sizeOfIntersection > $halfSizeOfLargest ? self::$h1TitleBonusWeight : 0;
        }

        return $h1->setScore("headline", "h1", $score);
    }

    /**
     * title scoring heuristic
     *
     * @param AiCrawler $node
     * @param Considerations $considerations
     * @return AiCrawler
     */
    private static function title(AiCrawler &$title, Considerations $considerations) {
        $title->setConsiderFor("headline");
        $title->setScore("headline", "title", self::$titleWeight);
        return $title;
    }

}