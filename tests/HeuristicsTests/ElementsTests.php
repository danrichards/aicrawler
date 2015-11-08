<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

class ElementsTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_is_a_p_elements()
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
        Heuristics::elements($node);
    }

    /**
     * @test
     */
    public function it_is_an_element_in_a_list_with_string_provided()
    {
        $args['elements'] = 'div p';
        $node = $this->crawler->filter('p')->first();
        $this->assertTrue(Heuristics::elements($node, $args));
    }

    /**
     * @test
     */
    public function it_is_an_element_in_a_string_of_patterns_provided()
    {
        $args['regex'] = true;
        $args['elements'] = '/div/ /h[1-6]/';
        $node = $this->crawler->filter('h1')->first();
        $this->assertTrue(Heuristics::elements($node, $args));
    }

    /**
     * @test
     */
    public function it_is_an_element_in_an_array_of_patterns_provided()
    {
        $args['regex'] = true;
        $args['elements'] = ['/div/','/h[1-6]/'];
        $node = $this->crawler->filter('h1')->first();
        $this->assertTrue(Heuristics::elements($node, $args));
    }

    /**
     * @test
     */
    public function it_is_an_element_in_a_list_of_elements_provided()
    {
        $args['elements'] = ['div', 'p'];
        $node = $this->crawler->filter('p')->first();
        $this->assertTrue(Heuristics::elements($node, $args));
    }

    /**
     * @test
     */
    public function it_is_an_not_an_element_in_a_list_of_elements_provided()
    {
        $args['elements'] = ['a'];
        $node = $this->crawler->filter('p')->first();
        $this->assertFalse(Heuristics::elements($node, $args));
    }

}