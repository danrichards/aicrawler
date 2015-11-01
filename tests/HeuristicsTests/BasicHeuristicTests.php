<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;

class BasicHeuristicTests extends HeuristicsTestCase
{
    /**
     * Instantiate a new crawler
     *
     * @test
     */
    public function it_has_a_crawler()
    {
        $this->assertTrue(is_object($this->crawler));
        $this->assertInstanceOf(AiCrawler::class, $this->crawler);
    }
}