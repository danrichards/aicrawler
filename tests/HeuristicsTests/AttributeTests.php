<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

class AttributeTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_has_any_of_the_default_attributes()
    {
        $node = $this->crawler->filter('nav')->first();
        $this->assertTrue(Heuristics::attribute($node));
    }

    /**
     * @test
     */
    public function it_has_all_of_a_specific_set_of_attributes()
    {
        $args['matches'] = 'all';
        $args['attributes'] = ['id', 'class'];
        $node = $this->crawler->filter('nav')->first();
        $this->assertTrue(Heuristics::attribute($node, $args));
    }

    /**
     * @test
     */
    public function it_has_any_of_a_specific_set_of_attributes()
    {
        $arg['matches'] = 'any';
        $args['attributes'] = ['id', 'banana'];
        $node = $this->crawler->filter('nav')->first();
        $this->assertTrue(Heuristics::attribute($node, $args));
    }

    /**
     * @test
     */
    public function it_has_at_least_n_of_a_specific_set_of_attributes()
    {
        $args['matches'] = 1;
        $args['attributes'] = ['id', 'banana'];
        $node = $this->crawler->filter('nav')->first();
        $this->assertTrue(Heuristics::attribute($node, $args));
    }

    /**
     * @test
     */
    public function it_has_none_of_a_specific_set_of_attributes()
    {
        $args['matches'] = 'none';
        $args['attributes'] = ['banana', 'sandwich'];
        $node = $this->crawler->filter('nav')->first();
        $this->assertTrue(Heuristics::attribute($node, $args));
    }
}