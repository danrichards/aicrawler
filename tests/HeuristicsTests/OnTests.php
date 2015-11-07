<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class OnTests
 *
 * @todo Write more unit tests :p
 */
class OnTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_has_children_with_no_args_specified()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first();
//        Heuristics::
    }

    /**
     * @test
     */
    public function it_has_no_children_with_no_args_specified()
    {

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