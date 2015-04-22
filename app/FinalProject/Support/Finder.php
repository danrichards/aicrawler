<?php
namespace FinalProject\Support;

/**
 * Class Support
 *
 * @package FinalProject\Commands
 */
class Finder {

    /**
     * Search nodes and return an array of FinderResult objects
     *
     * @param $node
     * @param FinderResult $result
     * @return string
     */
    public static function matches($node, $match = "body", $results = []) {
        /**
         * Setup our vars
         */
        if ($node->nodeName() === $match)
            $results[] = new FinderResult($node, true);

        /**
         * BFS Recursion
         */
        $node->children()->each(function ($n, $i) use ($match, &$results) {
            $matches = Finder::matches($n, $match);
            if (count($matches))
                $results = array_merge($results, $matches);
        });

        return $results;
    }

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
        try {
            /**
             * Setup our vars
             */
            $text = "";
            $tabs = str_repeat("   ", $depth);
            $tag = $node->nodeName();
            $children = $node->children()->count();
            $parents = $node->parents()->count();
            $siblings = $node->siblings()->count();
            $show = array_key_exists("show", $extra) && is_array($extra['show']) ? $extra['show'] : [];
            $only = array_key_exists("only", $extra) && is_array($extra['only']) ? $extra['only'] : null;
            // only cannot be combined with except
            $except = is_null($only) && array_key_exists("except", $extra) && is_array($extra['except']) ? $extra['except'] : null;

            /**
             * Conditions for further traversal
             */
            if ( (is_array($only) && in_array($tag, $only))
                || (is_array($except) && !in_array($tag, $except))
                || ($only == null && $except == null)
            ) {
                $text .= $tabs . $tag . " ";
                $numbers = [];
                if (in_array("depth", $show))
                    $numbers[] = "Depth(".$depth.")";
                if (in_array("parents", $show))
                    $numbers[] = "Parents(".$parents.")";
                if (in_array("children", $show))
                    $numbers[] = "Children(".$children.")";
                if (in_array("siblings", $show))
                    $numbers[] = "Siblings(".$siblings.")";
                $text .= count($numbers) ? "- " : "";
                $text .= implode(", ", $numbers);
                $text .= in_array("preview", $show) ? " ~ ".substr(preg_replace('/\s+/', ' ', $node->text()) ,0,140)." " : "";
                $text .= "\n";
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
        catch (\InvalidArgumentException $e) {
            return "No More Nodes.";
        }
    }
}