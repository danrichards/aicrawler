<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;
use Dan\AiCrawler\Heuristics;

/**
 * Class OverloadHeuristics
 *
 * @package AiCrawlerTests\ExtendsHeuristicsTests
 *
 * How to override something properly in the Heuristics class.
 */
class OverloadHeuristics extends Heuristics
{
    /**
     * Overload the default args for the method.
     *
     * @var array
     */
    protected static $characters = [
        'matches' => 1
    ];

    /**
     * Overload characters function
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return string
     */
    public static function characters(AiCrawler &$node, $args = [])
    {
        $matches = static::arg($args, 'matches');
        return strlen($node->text()) > $matches;
    }
}

class OverloadTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_has_a_new_characters_method()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args['matches'] = 100;
        $this->assertTrue(OverloadHeuristics::characters($node, $args));

        $args['matches'] = 1000000;
        $this->assertFalse(OverloadHeuristics::characters($node, $args));
    }

}