<?php

namespace Dan\AiCrawler\Heuristics;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;

class HrefHeuristic extends AbstractHeuristic implements HeuristicInterface {

    private static $recurrencePenalty = 0.9;
    private static $hrefWeight = 1;

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
            case "a":
                $result = self::a($node, $considerations);
                break;
            default:
                $result = null;
        }

        return $result;
    }

    /**
     * h1 scoring heuristic
     *
     * @param AiCrawler $node
     * @param Considerations $considerations
     * @return $this
     */
    private static function a(AiCrawler &$a, Considerations $considerations) {
        $a->setConsiderFor("href");
        $score = 0;

        /**
         * Subsequent a tags are penalized
         */
        if ($considerations->getTagsCount("a")) {
            $score += $considerations->last()->getScoreTotal("href") * self::$recurrencePenalty;
        /**
         * The first a occurrence
         */
        } else {
            $score += self::$hrefWeight;
        }

        return $h1->setScore("href", "a", $score);
    }

}