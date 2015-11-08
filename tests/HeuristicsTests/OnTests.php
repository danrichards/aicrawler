<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;
use Dan\AiCrawler\Heuristics;

class CustomAiCrawler extends AiCrawler
{
    public function even()
    {

    }
}

/**
 * Class OnTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class OnTests extends HeuristicsTestCase
{

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_when_subset_method_does_not_exist()
    {
        $args['on'] = 'ancestors';
        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $this->assertTrue(Heuristics::on($node, []));
    }

    /**
     * @test
     */
    public function it_has_no_args_specified()
    {
        $args['on'] = 'children';
        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $this->assertTrue(Heuristics::children($node, []));

        $node = $this->crawler->filter('div[id="content_start"]')->first();
        $this->assertFalse(Heuristics::children($node, []));
    }

    /**
     * @test
     */
    public function it_has_children_with_only_min_children_specified()
    {

    }

    /**
     * @test
     */
    public function it_has_no_children_with_only_min_children_specified() {

    }

    /**
     * @test
     */
    public function it_has_children_with_subset_functions_specified() {

    }

    /**
     * @test
     */
    public function it_has_children_with_multiple_subset_functions_specified() {

    }

}