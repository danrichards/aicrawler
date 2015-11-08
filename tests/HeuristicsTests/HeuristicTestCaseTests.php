<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;

/**
 * Class HeuristicTestCaseTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class HeuristicTestCaseTests extends HeuristicsTestCase
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

    /**
     * @test
     */
    public function it_has_html_content()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $this->assertTrue($node->children()->count() > 0);
    }
}