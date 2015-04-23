<?php
namespace FinalProject\Heuristics;

use FinalProject\Support\Articrawler;
use FinalProject\Support\Considerations;

class HeadlineHeuristic {

    public static $recurrencePenalty = 0.75;

    public static $config = [
        'h1' => array()
    ];

    /**
     * Run the Heuristic. May augment considerations. May augment Scraper $nodes.
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     * @return bool|Articrawler
     */
    public static function run(Articrawler &$node, Considerations $considerations)
    {
        $name = $node->nodeName();
        $text = $node->text();
        // print $name.": ".substr(regex_remove_extraneous_whitespace($text), 0, 50)."\n";

        /**
         * h1 tag is our best guess
         */
        if ($name == "h1") {
            $node->setConsiderFor("headline");
            /**
             * Subsequent h1 tags are penalized
             */
            if ($considerations->getTagsCount("h1")) {
                $last = $considerations->filter(function ($n, $i) {
                    return $n->nodeName() == "h1";
                })->last();
                $node->setScore("headline", $last->getScore("headline") * static::$recurrencePenalty);

            } else {
                /**
                 * lexicalPenalty is enforced if
                 */
                $node->setScore("headline", 1 - lexicalPenalty($text, 0.5, 5, 1));
            }
            return $node;
        }

        /**
         * The title tag is a good fall back.
         */
        if ($name == "title") {
            $node->setConsiderFor("headline");
            $node->setScore("headline", 0.9);
            return $node;
        }

        /**
         * If things get really mucky we can look at the attributes
         */

        return false;
    }
}