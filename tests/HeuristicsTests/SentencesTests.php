<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;
use Dan\AiCrawler\Heuristics;

/**
 * Class sentencesTests
 *
 * Sample
 *  29 normal
 *  3 question
 *  exclamatory
 */
class SentencesTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_gets_no_args()
    {
        $args['children'] = true;

        $node = $this->crawler->filter('div[id="content_start"]');
        $this->assertFalse(Heuristics::sentences($node, $args));
        $this->assertFalse(Heuristics::questions($node, $args));
        $this->assertFalse(Heuristics::exclamatory($node, $args));

        $node = $this->crawler->filter('div[class="entry-content"]');
        $this->assertTrue(Heuristics::sentences($node, $args));
        $this->assertTrue(Heuristics::questions($node, $args));
        $this->assertTrue(Heuristics::exclamatory($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_an_integer_sentences_param()
    {
        $node = $this->crawler->filter('div[id="content_start"]');

        $args['matches'] = 0;
        $this->assertTrue(Heuristics::sentences($node, $args));
        $this->assertTrue(Heuristics::questions($node, $args));
        $this->assertTrue(Heuristics::exclamatory($node, $args));

        $node = $this->crawler->filter('div[class="entry-content"]');

        $args['children'] = true;

        $args['matches'] = 2;
        $this->assertTrue(Heuristics::sentences($node, $args));
        $this->assertTrue(Heuristics::questions($node, $args));
        $this->assertTrue(Heuristics::exclamatory($node, $args));

        $args['matches'] = 100;
        $this->assertFalse(Heuristics::sentences($node, $args));
        $this->assertFalse(Heuristics::questions($node, $args));
        $this->assertFalse(Heuristics::exclamatory($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_a_search_param()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args['children'] = true;

        $args['matches'] = 'any';
        $args['sentences'] = 'language';
        $args['questions'] = 'language';
        $args['exclamatory'] = 'ideas';
        $this->assertTrue(Heuristics::sentences($node, $args));
        $this->assertTrue(Heuristics::questions($node, $args));
        $this->assertTrue(Heuristics::exclamatory($node, $args));

        $args['sentences'] = $args['questions'] = $args['exclamatory'] = 'zzz';
        $this->assertFalse(Heuristics::sentences($node, $args));
        $this->assertFalse(Heuristics::questions($node, $args));
        $this->assertFalse(Heuristics::exclamatory($node, $args));

        $args['regex'] = true;

        $args['sentences'] = '/language/';
        $args['questions'] = '/language/';
        $args['exclamatory'] = '/ideas/';
        $this->assertTrue(Heuristics::sentences($node, $args));
        $this->assertTrue(Heuristics::questions($node, $args));
        $this->assertTrue(Heuristics::exclamatory($node, $args));

        $args['sentences'] = $args['questions'] = $args['exclamatory'] = '/zzz/';
        $this->assertFalse(Heuristics::sentences($node, $args));
        $this->assertFalse(Heuristics::questions($node, $args));
        $this->assertFalse(Heuristics::exclamatory($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_a_min_words_param()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args['children'] = true;
        $args['matches'] = 3;
        $args['min_words'] = 30;
        $this->assertTrue(Heuristics::sentences($node, $args));

        $args['matches'] = 4;
        $this->assertFalse(Heuristics::sentences($node, $args));
    }

}