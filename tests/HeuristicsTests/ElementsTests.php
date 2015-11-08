<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class ElementsTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class ElementsTests extends HeuristicsTestCase
{

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