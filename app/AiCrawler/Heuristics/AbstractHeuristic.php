<?php namespace AiCrawler\Heuristics;

use AiCrawler\Support\AiCrawler;
use AiCrawler\Support\Considerations;

/**
 * All heuristics will implement these methods or override them with respect to their contexts.
 *
 * @package AiCrawler\Heuristics
 */
abstract class AbstractHeuristic implements HeuristicInterface
{

    /**
     * Score your nodes.
     *
     * @param AiCrawler $node
     * @param Considerations $c
     * @return Considerations
     */
    abstract public static function score(AiCrawler &$node, Considerations $c);

}