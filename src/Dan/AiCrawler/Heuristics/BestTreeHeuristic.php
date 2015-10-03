<?php

namespace Dan\AiCrawler\Heuristics;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;

class BestTreeHeuristic extends AbstractHeuristic implements HeuristicInterface{

    /**
     * Score your nodes.
     *
     * @param AiCrawler $node
     * @param Considerations $c
     * @todo Write a Heuristic that you provide an array of filters and it scores the occurrences based on how the order they appear in the given array.
     *
     * @return AiCrawler
     */
    public static function score(AiCrawler &$node, Considerations $c)
    {
        // TODO: Implement score() method.
    }

}