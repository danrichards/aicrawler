<?php namespace AiCrawler\Heuristics;

use AiCrawler\Support\AiCrawler;
use AiCrawler\Support\Considerations;

/**
 * Score nodes based on their likeliness to be the node that contains an article's image.
 *
 * @package AiCrawler\Heuristics
 */
class ClosestImageHeuristic extends AbstractHeuristic {

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