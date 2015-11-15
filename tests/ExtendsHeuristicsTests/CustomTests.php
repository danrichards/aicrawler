<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;
use Dan\AiCrawler\Heuristics;
use InvalidArgumentException;

/**
 * Class CustomHeuristics
 *
 * @package AiCrawlerTests\ExtendsHeuristicsTests
 *
 * How to override something properly in the Heuristics class.
 */
class CustomHeuristics extends Heuristics
{
    /**
     * Defaults for custom even() heuristic.
     *
     * @var array
     */
    protected static $even = [];

    /**
     * Defaults for custom odd() heuristic.
     *
     * @var array
     */
    protected static $odd = [];

    /**
     * Even function. Determine if a node is even among its siblings.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return bool
     */
    public static function even(AiCrawler &$node, array $args = [])
    {
        try {
            return $node->previousAll()->count() % 2 != 0;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Custom odd function.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return string
     */
    public static function odd(AiCrawler &$node, array $args = [])
    {
        try {
            return $node->previousAll()->count() % 2 == 0;
        } catch (InvalidArgumentException $e) {
            return true;
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

    /**
     * @test
     */
    public function it_is_a_odd_node_amongst_its_siblings()
    {
        $node = $this->crawler->filter('div[class="entry-content"] p')->first();
        $args = [];

        $this->assertTrue(CustomHeuristics::odd($node, $args));

        $node = $this->crawler->filter('div[class="entry-content"] h2')->first();
        $this->assertFalse(CustomHeuristics::odd($node, $args));
    }

}