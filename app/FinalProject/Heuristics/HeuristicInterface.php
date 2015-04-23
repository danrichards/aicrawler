<?php
namespace FinalProject\Heuristics;

/**
 * Some general rules for our Heuristics to abide by.
 *
 * @package FinalProject\Heuristics
 */
interface HeuristicInterface {

    /**
     * Run the Heuristic. May augment considerations. May augment Scraper $nodes.
     */
    public static function run(&$considerations);

}