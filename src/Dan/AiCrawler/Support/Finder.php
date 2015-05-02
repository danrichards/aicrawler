<?php namespace Dan\AiCrawler\Support;

/**
 * Class Support
 *
 * @package AiCrawler\Commands
 */
class Finder {


    /**
     * Ghetto, but ok.
     *
     * @param $node
     * @param string $match
     */
    public static function first($node, $match = "body") {
            $matches = Finder::matches($node, $match);
            return (count($matches)) ? $matches[0] : [];
    }

    /**
     * Ghetto, but ok.
     *
     * @param $node
     * @param string $match
     */
    public static function last($node, $match = "body") {
        $matches = Finder::matches($node, $match);
        return (count($matches)) ? $matches[count($matches) - 1] : [];
    }

    /**
     * Perform Bread-first Search on DomCrawler / Articrawl Node
     *
     * @param $node
     * @param array $extra
     * @param int $stop
     * @param int $depth
     * @return string
     */
    public static function bfsOutput($node, $extra = [], $stop = 100, $depth = 0) {
        /**
         * Setup our vars
         */
        $only = array_key_exists("only", $extra) && is_array($extra['only']) ? $extra['only'] : null;
        // only cannot be combined with except
        $except = is_null($only) && array_key_exists("except", $extra) && is_array($extra['except']) ? $extra['except'] : null;

        /**
         * Conditions for further traversal
         */
        $text = "";
        if ( (is_array($only) && in_array($node->nodeName(), $only))
            || (is_array($except) && !in_array($node->nodeName(), $except))
            || ($only == null && $except == null)
        ) {
            $text = self::output($node, $extra, $depth, $text);
            /**
             * BFS Recursion
             */
            if (!is_numeric($stop) || $depth < $stop) {
                $node->children()->each(function ($n, $i) use ($extra, $stop, $depth, &$text) {
                    $text .= Finder::bfsOutput($n, $extra, $stop, $depth + 1);
                });
            }
        }
        return $text;
    }

    /**
     * @param $node
     * @param $extra
     * @param $depth
     * @param $text
     * @return string
     */
    private static function output($node, $extra, $depth, $text)
    {
        /**
         * Text Output
         */
        $tabs = str_repeat("   ", $depth);
        $text .= $tabs.$node->nodeName();
        $show = $extra['show'];
        $numbers = [];
        
        if (count(array_intersect(['parents', 'p', 'all', 'a'], $show)))
            $numbers[] = "Parents(" . $node->parents()->count() . ")";
        if (count(array_intersect(['children', 'c', 'all', 'a'], $show)))
            $numbers[] = "Children(" . $node->children()->count() . ")";
        if (count(array_intersect(['siblings', 's', 'all', 'a'], $show)))
            $numbers[] = "Siblings(" . $node->siblings()->count() . ")";
        if (count(array_intersect(['depth', 'd', 'all', 'a'], $show)))
            $numbers[] = "Depth(" . $depth . ")";
        if (count(array_intersect(['words', 'w', 'all', 'a'], $show)))
            $numbers[] = "Words(" . $node->numWords() . ")";
        if (count(array_intersect(['sentences', 'sn', 'all', 'a'], $show)))
            $numbers[] = "Sentences(" . $node->numSentences() . ")";
        if (count(array_intersect(['paragraphs', 'pg', 'all', 'a'], $show)))
            $numbers[] = "Paragraphs(" . $node->numParagraphs() . ")";

        $text .= count($numbers) ? "- " : "";
        $text .= implode(", ", $numbers);
        if (count(array_intersect(['text', 't', 'all', 'a'], $show)))
            $text .= "\n".$tabs."\t\tTEXT: ".substr(regex_remove_extraneous_whitespace($node->text()), 0, 140);
        if (count(array_intersect(['html', 'h', 'all', 'a'], $show)))
            $text .= "\n".$tabs."\t\tHTML: ".substr(regex_remove_extraneous_whitespace($node->html()), 0, 140);
        $text .= "\n";
        return $text;
    }
}