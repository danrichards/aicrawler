<?php namespace AiCrawler\Heuristics;

use AiCrawler\Support\AiCrawler;
use AiCrawler\Support\Considerations;

/**
 * Some general rules for our Heuristics to abide by.
 *
 * @package AiCrawler\Heuristics
 */
interface HeuristicInterface {

    /**
     * Score your nodes.
     *
     * @param AiCrawler $node
     * @param Considerations $c
     * @return AiCrawler
     */
    public static function score(AiCrawler &$node, Considerations $c);

}