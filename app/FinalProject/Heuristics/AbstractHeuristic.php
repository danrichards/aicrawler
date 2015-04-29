<?php namespace FinalProject\Heuristics;

use FinalProject\Support\Articrawler;
use FinalProject\Support\Considerations;

/**
 * All heuristics will implement these methods or override them with respect to their contexts.
 *
 * @package FinalProject\Heuristics
 */
abstract class AbstractHeuristic implements HeuristicInterface
{

    /**
     * Drop an nodes that that will jumble our data abstraction. Merge singular nodes with others were beneficial.
     *
     * @param Articrawler $top
     * @return Articrawler
     */
    public static function prep(Articrawler $top) {
        return $top;
    }

    /**
     * Score your nodes.
     *
     * @param Articrawler $node
     * @param Considerations $c
     * @return Considerations
     */
    public static function score(Articrawler &$node, Considerations $c) {
        return $c;
    }

    /**
     * Last change to examine all the considerations that were scored and return one.
     *
     * @param Considerations $c
     * @return mixed|null
     */
    public static function choose(Considerations $c) {
        return $c->first();
    }

    /**
     * Last change to examine all the considerations that were scored and return a collection.
     *
     * @param Considerations $c
     * @return Considerations
     */
    public static function chooseMany(Considerations $c) {
        return $c;
    }

}