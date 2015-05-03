<?php namespace Dan\AiCrawler\Heuristics;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;

class ClosestAuthorHeuristic extends AbstractHeuristic implements HeuristicInterface {

    /**
     * Score your nodes.
     *
     * @param AiCrawler $node
     * @param Considerations $c
     * @return Considerations
     */
    public static function score(AiCrawler &$node, Considerations $c)
    {
        // TODO: Implement score() method.
    }

}