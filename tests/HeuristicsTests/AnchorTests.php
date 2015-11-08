<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class AnchorTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class AnchorTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_is_an_anchor_element_with_no_domain_arg()
    {
        $node = $this->crawler->filter('a')->first();
        $this->assertTrue(Heuristics::a($node));
    }

    /**
     * @test
     */
    public function it_is_an_anchor_element_with_matching_domain_arg()
    {
        $args['domain'] = 'oreilly.com';
        $node = $this->crawler->filter('a')->first();
        $this->assertTrue(Heuristics::a($node, $args));
    }

    /**
     * @test
     */
    public function it_is_an_anchor_element_with_mismatching_domain_arg()
    {
        $args['domain'] = 'banana-sandwich.com';
        $node = $this->crawler->filter('a')->first();
        $this->assertFalse(Heuristics::a($node, $args));
    }

}