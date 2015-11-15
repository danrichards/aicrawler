<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class NestedTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class NestedTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_has_one_other_rule()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first();

        $args = [
            'elements' => 'div',
            'words' => [
                'words' => 5
            ]
        ];
        $this->assertTrue(Heuristics::elements($node, $args));

        $args = [
            'elements' => 'div',
            'words' => [
                'words' => 100
            ]
        ];
        $this->assertFalse(Heuristics::elements($node, $args));
    }

    /**
     * @test
     */
    public function it_has_two_other_rules()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first();

        $args = [
            'elements' => 'div',
            'words' => [
                'words' => 5
            ],
            'words2' => [
                'words' => "open source",
                'descendants' => true
            ]
        ];
        $this->assertTrue(Heuristics::elements($node, $args));

        $args = [
            'elements' => 'div',
            'words' => [
                'words' => 5
            ],
            'words2' => [
                'words' => 'banana sandwich',
                'descendants' => true
            ]
        ];
        $this->assertFalse(Heuristics::elements($node, $args));
    }

    /**
     * @test
     */
    public function it_has_a_rule_within_a_rule()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first();

        $args = [
            'elements' => 'div',
            'words' => [
                'words' => 5,
                'words2' => [
                    'words' => "open source",
                    'descendants' => true
                ]
            ]
        ];
        $this->assertTrue(Heuristics::elements($node, $args));

        $args = [
            'elements' => 'div',
            'words' => [
                'words' => 5,
                'words2' => [
                    'words' => 'banana sandwich',
                    'descendants' => true
                ]
            ]
        ];
        $this->assertFalse(Heuristics::elements($node, $args));
    }

}