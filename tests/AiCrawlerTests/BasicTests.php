<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;

/**
 * Class ParentsTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class BasicTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_removes_children()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $this->assertTrue($node->children()->count() > 0);

        $node = $this->crawler->filter('div[class="entry-content"]')
            ->first()->createChildlessSubCrawler();

        $this->assertTrue($node->children()->count() == 0);
    }

    /**
     * @test
     */
    public function it_does_not_effect_the_original_crawler()
    {
        $node = $this->crawler->filter('div[class="entry-content"]')->first();
        $this->assertTrue($node->children()->count() > 0);

        $anotherCrawler = $this->crawler
            ->filter('div[class="entry-content"]')
            ->createChildlessSubCrawler();

        $this->assertTrue($anotherCrawler->children()->count() == 0);
        $this->assertTrue($node->children()->count() > 0);
    }

    /**
     * @test
     */
    public function it_gets_multiple_attributes()
    {
        $node = $this->crawler->filter('nav')->first();
        $attributes = $node->attributes(['id', 'class']);
        $this->assertEquals(2, count(array_intersect_key($attributes, ['id' => 1, 'class' => 1])));
        $this->assertEquals(2, count(array_intersect($attributes, ['mobi', 'menu-main-nav-container'])));
    }

}