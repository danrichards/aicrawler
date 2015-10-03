<?php

namespace Dan\AiCrawler\Heuristics;

use Dan\AiCrawler\Support\AiCrawler;
use Dan\AiCrawler\Support\Considerations;

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


    public static function response(AiCrawler $node, $context, $scraperExtra = []);
}