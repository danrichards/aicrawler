<?php

namespace Dan\AiCrawler\Heuristics;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;

/**
 * Score nodes based on their likeliness to be the node that contains an article's image.
 *
 * @package AiCrawler\Heuristics
 */
class ClosestImageHeuristic extends AbstractHeuristic implements HeuristicInterface {

    /**
     * Score your nodes.
     *
     * @param AiCrawler $node
     * @param Considerations $c
     * @return Considerations
     */
    public static function score(AiCrawler &$node, Considerations $c) {
        return null;
    }

}