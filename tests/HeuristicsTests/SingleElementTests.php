<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

class SingleElementTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_is_a_p_element()
    {
        $node = $this->crawler->filter('p')->first();
        $this->assertTrue(Heuristics::p($node));
    }

    /**
     * @test
     */
    public function it_is_a_a_element_with_no_domain_arg()
    {
        $node = $this->crawler->filter('a')->first();
        $this->assertTrue(Heuristics::a($node));
    }

    /**
     * @test
     */
    public function it_is_a_a_element_with_matching_domain_arg()
    {
        $args['domain'] = 'oreilly.com';
        $node = $this->crawler->filter('a')->first();
        $this->assertTrue(Heuristics::a($node, $args));
    }

    /**
     * @test
     */
    public function it_is_a_a_element_with_mismatching_domain_arg()
    {
        $args['domain'] = 'banana-sandwich.com';
        $node = $this->crawler->filter('a')->first();
        $this->assertFalse(Heuristics::a($node, $args));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_when_elements_is_not_provided()
    {
        $node = $this->crawler->filter('p')->first();
        Heuristics::element($node);
    }

    /**
     * @test
     */
    public function it_is_an_element_in_a_list_with_string_provided()
    {
        $args['elements'] = 'p';
        $node = $this->crawler->filter('p')->first();
        $this->assertTrue(Heuristics::element($node, $args));
    }

    /**
     * @test
     */
    public function it_is_an_element_in_a_list_of_elements_provided()
    {
        $args['elements'] = ['p'];
        $node = $this->crawler->filter('p')->first();
        $this->assertTrue(Heuristics::element($node, $args));
    }

    /**
     * @test
     */
    public function it_is_an_not_an_element_in_a_list_of_elements_provided()
    {
        $args['elements'] = ['a'];
        $node = $this->crawler->filter('p')->first();
        $this->assertFalse(Heuristics::element($node, $args));
    }

}