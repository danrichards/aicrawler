<?php

namespace AiCrawlerTests\HeuristicsTests;
use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class AfterHittingTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class AfterHittingTests extends HeuristicsTestCase
{

    /**
     * @test
     *
     * @expectedException InvalidArgumentException
     */
    public function it_throws_exception_with_no_item_provided()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first()
            ->setDataPoint("test", "first", 1)
            ->setDataPoint("test", "second", 100)
            ->setDataPoint("test", "third", 15)
            ->setDataPoint("test", "fourth", 20);

        Heuristics::after_hitting($node, []);
    }

    /**
     * @test
     */
    public function it_does_not_specify_data_points()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first()
            ->setDataPoint("test", "first", 1)
            ->setDataPoint("test", "second", 100)
            ->setDataPoint("test", "third", 15)
            ->setDataPoint("test", "fourth", 20);

        $args = ['item' => 'test'];

        $args['matches'] = "all";
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "any";
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = 1;
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "none";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 0;
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $node->setDataPoint("test", "fifth", 0);

        $args['matches'] = "all";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $node->removeItem('test');

        $args['matches'] = "any";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 1;
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "none";
        $this->assertTrue(Heuristics::after_hitting($node, $args));
    }

    /**
     * @test
     */
    public function it_specifies_data_points()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first()
            ->setDataPoint("test", "first", 1)
            ->setDataPoint("test", "second", 100)
            ->setDataPoint("test", "third", 15)
            ->setDataPoint("test", "fourth", 20);

        $args = [
            'item' => 'test',
            'data_points' => 'first second third'
        ];

        $args['matches'] = "all";
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "any";
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = 1;
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "none";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 0;
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $node->setDataPoint("test", "fifth", 0);

        $args['data_points'] = 'first second third fifth';

        $args['matches'] = "all";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $node->removeItem('test');

        $args['matches'] = "any";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 1;
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "none";
        $this->assertTrue(Heuristics::after_hitting($node, $args));
    }

    /**
     * @test
     */
    public function it_specifies_data_points_with_assoc_array()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first()
            ->setDataPoint("test", "first", 1)
            ->setDataPoint("test", "second", 100)
            ->setDataPoint("test", "third", 15)
            ->setDataPoint("test", "fourth", 20);

        $args = [
            'item' => 'test',
            'data_points' => [
                'first' => 1,
                'second' => 100,
                'third' => 15,
                'fourth' => 20
            ]
        ];

        $args['matches'] = "all";
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "any";
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = 1;
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "none";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 0;
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['data_points']['fourth'] = 21;

        $args['matches'] = "all";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['data_points'] = [
            'first' => 2,
            'second' => 101,
            'third' => 16
        ];

        $args['matches'] = "any";
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 1;
        $this->assertFalse(Heuristics::after_hitting($node, $args));

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::after_hitting($node, $args));

        $args['matches'] = "none";
        $this->assertTrue(Heuristics::after_hitting($node, $args));
    }

}