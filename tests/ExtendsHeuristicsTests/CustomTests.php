<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;
use Dan\AiCrawler\Heuristics;
use InvalidArgumentException;

/**
 * Class OverloadHeuristics
 *
 * @package AiCrawlerTests\ExtendsHeuristicsTests
 *
 * How to override something properly in the Heuristics class.
 */
class CustomHeuristics extends Heuristics
{
    /**
     * Return true if this node is even amongst its siblings.
     *
     * @var array
     */
    protected static $even = [];

    /**
     * Overload characters function
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return string
     */
    public static function even(AiCrawler &$node, array $args = [])
    {
        try {
            return $node->previousAll()->count() % 2 != 0;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}

class CustomTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_is_a_even_node_amongst_its_siblings()
    {
        $node = $this->crawler->filter('div[class="entry-content"] p')->first();
        $args = [];

        $this->assertFalse(CustomHeuristics::even($node, $args));

        $node = $this->crawler->filter('div[class="entry-content"] h2')->first();
        $this->assertTrue(CustomHeuristics::even($node, $args));
    }

}