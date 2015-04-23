<?php namespace FinalProject\Heuristics;

use FinalProject\Support\Articrawler;
use FinalProject\Support\Considerations;

class HeadlineHeuristic implements HeuristicInterface {

    private static $lexicalPenalty = 0.5;
    private static $recurrencePenalty = 0.75;

    private static $titleWeight = 0.9;
    private static $h1Weight = 1;
    private static $h1MinWords = 1;
    private static $h1MinCharacters = 5;

    /**
     * Run the Heuristic. Return a node to consider or false.
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     * @return bool|Articrawler
     */
    public static function run(Articrawler &$node, Considerations $considerations) {
        /**
         * We can define methods to handle tags. I was going to use method_exists and call_user_func_array and execute
         * methods dynamically, but the call_user_func_array does not support TypeHints (which are necessary)
         */
        switch ($node->nodeName()) {
            case "h1":
                $result = static::h1($node, $considerations);
                break;
            case "title":
                $result = static::title($node, $considerations);
                break;
            default:
                $result = static::attribute($node, $considerations);
        }

        return $result;
    }

    /**
     * h1 scoring heuristic
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     * @return $this
     */
    private static function h1(Articrawler &$node, Considerations $considerations) {
        $node->setConsiderFor("headline");

        /**
         * Subsequent h1 tags are penalized
         */
        if ($considerations->getTagsCount("h1")) {
            // Get the last occurrence
            $last = $considerations->filter(function ($n, $i) {
                return $n->nodeName() == "h1";
            })->last();

            // If the last occurrence was lexically penalized, don't apply a recurrence penalty
            if (lexicalPenalty($last->text(), true, static::$h1MinCharacters, static::$h1MinWords) != 0) {
                $score = $last->getScoreTotal("headline") * static::$recurrencePenalty;
                return $node->setScore("headline", "h1", $score);
            }
        }

        /**
         * First h1 occurrence is only subject to the lexicalPenalty
         */
        $score = static::$h1Weight - lexicalPenalty($node->text(), static::$lexicalPenalty, static::$h1MinCharacters, static::$h1MinWords);
        return $node->setScore("headline", "h1", $score);
    }

    /**
     * title scoring heuristic
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     * @return Articrawler
     */
    private static function title(Articrawler &$node, Considerations $considerations) {
        $node->setConsiderFor("headline");
        $node->setScore("headline", "title", static::$titleWeight);
        return $node;
    }

    /**
     * attribute scoring heuristic
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     */
    private static function attribute(Articrawler &$node, Considerations $considerations) {
        return false;
    }
}