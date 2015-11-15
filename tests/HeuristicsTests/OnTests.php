<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

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
    public function it_has_children_with_matches_specified_but_no_rules()
    {

        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $args['on'] = 'children';

        $args['matches'] = 'all';
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 5;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 25;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertFalse(Heuristics::on($node, $args));

        $node = $this->crawler->filter('div[id="content_start"]')->first();

        $args['matches'] = 'all';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 1;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertTrue(Heuristics::on($node, $args));

    }

    /**
     * @test
     */
    public function it_has_matches_arg_with_a_single_rule() {

        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $args['on'] = 'children';
        $args['elements']['elements'] = 'p h2 div ul';

        $args['matches'] = 'all';
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 5;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 25;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertFalse(Heuristics::on($node, $args));

        $node = $this->crawler->filter('div[id="content_start"]')->first();
        $args['on'] = 'children';
        $args['elements']['elements'] = 'p';

        $args['matches'] = 'all';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 5;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertTrue(Heuristics::on($node, $args));

    }

    /**
     * @test
     */
    public function it_has_matches_arg_with_multiple_rules() {

        $node = $this->crawler->filter('div[class="entry-content"] ul')->first();

        /**
         * What's happening?
         *
         * The node provided should be a ul element, and have children which
         * are either li. Those children must all have at least 3 words.
         */
        $args = [
            'on' => 'children',
            'elements' => [
                'elements' => 'li',
                'matches' => 'any'
            ],
            'words' => [
                'words' => 3
            ],
            'matches' => 'all'
        ];

        $this->assertTrue(Heuristics::on($node, $args));

        $node = $this->crawler->filter('div[class="entry-content"]')->first();

        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 5;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 25;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertFalse(Heuristics::on($node, $args));

        $node = $this->crawler->filter('div[id="content_start"]')->first();
        $args['on'] = 'children';
        $args['elements']['elements'] = 'p';

        $args['matches'] = 'all';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 5;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertTrue(Heuristics::on($node, $args));

    }

    /**
     * @test
     */
    public function it_has_matches_arg_with_nested_rules() {

        $node = $this->crawler->filter('div[class="entry-content"] ul')->first();

        /**
         * What's happening?
         *
         * The node provided should be a ul element, and have children which
         * are either li. Those children must all have at least 3 words.
         */
        $args = [
            'on' => 'children',
            'elements' => [
                'elements' => '/li/',
                'regex' => true,
                'matches' => 'any',
                'words' => [
                    'words' => 3,
                    'descendants' => true
                ],
            ],
            'characters' => [
                'characters' => 10
            ],
            'matches' => 'all'
        ];

        $this->assertTrue(Heuristics::on($node, $args));

        $args['elements']['words']['words'] = 25;
        $this->assertFalse(Heuristics::on($node, $args));

        $node = $this->crawler->filter('div[class="entry-content"]')->first();

        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $args['elements']['elements'] = '/div/ /h[0-9]/ /ul/ /p/';
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 5;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 100;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'all';
        $args['elements']['elements'] = '/banana/ /sandwich/';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertTrue(Heuristics::on($node, $args));

        $node = $this->crawler->filter('div[id="content_start"]')->first();
        $args['on'] = 'children';
        $args['elements']['elements'] = 'p';

        $args['matches'] = 'all';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 'any';
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 5;
        $this->assertFalse(Heuristics::on($node, $args));

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::on($node, $args));

        $args['matches'] = 'none';
        $this->assertTrue(Heuristics::on($node, $args));

    }

}