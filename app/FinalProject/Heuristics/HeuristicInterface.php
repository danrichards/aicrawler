<?php namespace FinalProject\Heuristics;

use FinalProject\Support\Articrawler;
use FinalProject\Support\Considerations;

/**
 * Some general rules for our Heuristics to abide by.
 *
 * @package FinalProject\Heuristics
 */
interface HeuristicInterface {

    /**
     * Run the Heuristic. Return a node to consider or false.
     *
     * @param Articrawler $node
     * @param Considerations $considerations
     * @return bool|Articrawler
     */
    public static function run(Articrawler &$node, Considerations $considerations);

}