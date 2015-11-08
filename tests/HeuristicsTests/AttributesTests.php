<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class AttributesTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class AttributesTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_has_any_of_the_default_attributes()
    {
        $node = $this->crawler->filter('nav')->first();
        $this->assertTrue(Heuristics::attributes($node));
    }

    /**
     * @test
     */
    public function it_has_all_of_a_specific_set_of_attributes()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 'all';
        $args['attributes'] = ['id', 'class'];
        $this->assertTrue(Heuristics::attributes($node, $args));
    }

    /**
     * @test
     */
    public function it_has_any_of_a_specific_set_of_attributes()
    {
        $node = $this->crawler->filter('nav')->first();

        $arg['matches'] = 'any';
        $args['attributes'] = ['id', 'banana'];
        $this->assertTrue(Heuristics::attributes($node, $args));
    }

    /**
     * @test
     */
    public function it_has_at_least_n_of_a_specific_set_of_attributes()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 1;
        $args['attributes'] = ['id', 'banana'];
        $this->assertTrue(Heuristics::attributes($node, $args));
    }

    /**
     * @test
     */
    public function it_has_none_of_a_specific_set_of_attributes()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 'none';
        $args['attributes'] = ['banana', 'sandwich'];
        $this->assertTrue(Heuristics::attributes($node, $args));
    }
}