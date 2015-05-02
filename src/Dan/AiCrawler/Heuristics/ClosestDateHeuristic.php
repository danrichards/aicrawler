<?php namespace Dan\AiCrawler\Heuristics;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Heuristics\AbstractHeuristic;
use Dan\AiCrawler\Heuristics\HeuristicInterface;
use Dan\AiCrawler\Support\Considerations;

class DateHeuristic extends AbstractHeuristic {

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