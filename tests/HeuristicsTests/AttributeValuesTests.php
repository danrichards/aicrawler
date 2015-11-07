<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

class AttributeValuesTests extends HeuristicsTestCase
{

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_is_missing_the_values_arg()
    {
        $node = $this->crawler->filter('nav')->first();
        Heuristics::attribute_values($node, []);
    }

    /**
     * @test
     */
    public function it_matches_any_default_attributes_with_any_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['values'] = ['mobi'];
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values'] = ['banana'];
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_all_specific_attributes_with_any_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['attributes'] = ['id', 'class'];
        $args['values'] = ['mobi', 'menu-main-nav-container'];
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values'] = ['banana', 'sandwich'];
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_any_specific_attributes_with_any_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['attributes'] = ['id', 'class'];
        $args['values'] = ['mobi', 'menu-main-nav-container'];
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values'] = ['banana', 'sandwich'];
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_at_least_n_specific_attributes_with_any_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 1;
        $args['attributes'] = ['id', 'class'];
        $args['values'] = ['mobi', 'menu-main-nav-container'];
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values'] = ['banana', 'sandwich'];
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_no_specific_attributes_with_any_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 'none';
        $args['attributes'] = ['id', 'class'];
        $args['values'] = ['banana', 'sandwich'];
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values'] = ['mobi'];
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_all_associative_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 'all';
        $args['values'] = [
            'id' => 'mobi',
            'class' => 'menu-main-nav-container'
        ];
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values']['id'] = 'banana';
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_any_associative_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 'any';
        $args['values']['id'] = 'mobi';
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values']['id'] = 'banana';
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_at_least_n_associative_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 1;
        $args['values']['id'] = 'mobi';
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values']['id'] = 'banana';
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

    /**
     * @test
     */
    public function it_matches_no_associative_values()
    {
        $node = $this->crawler->filter('nav')->first();

        $args['matches'] = 'none';
        $args['values']['id'] = 'banana';
        $this->assertTrue(Heuristics::attribute_values($node, $args));

        // inverse
        $args['values']['id'] = 'mobi';
        $this->assertFalse(Heuristics::attribute_values($node, $args));
    }

}