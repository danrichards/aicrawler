<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;

/**
 * Class AiCrawlerTest
 *
 * @todo Write more unit tests :p
 */
class WithChildrenTest extends HeuristicsTestCase
{
    /**
     * Instantiate a new crawler
     *
     * @test
     */
    public function it_has_a_crawler()
    {
        $this->assertTrue(is_object($this->crawler));
    }
}