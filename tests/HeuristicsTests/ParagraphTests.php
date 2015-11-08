<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class ParagraphTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class ParagraphTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_is_a_paragraph_element()
    {
        $node = $this->crawler->filter('p')->first();
        $this->assertTrue(Heuristics::p($node));

        $node = $this->crawler->filter('div')->first();
        $this->assertFalse(Heuristics::p($node));
    }

}