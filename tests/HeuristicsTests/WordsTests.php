<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class WordsTests
 */
class WordsTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_gets_no_words()
    {
        $node = $this->crawler->filter('div[class="content_start"]');
        $this->assertFalse(Heuristics::words($node, ['matches' => 'any']));
        $this->assertFalse(Heuristics::words($node, ['matches' => 'all']));
        $this->assertFalse(Heuristics::words($node, ['matches' => 1]));
        $this->assertTrue(Heuristics::words($node, ['matches' => 0]));
        $this->assertTrue(Heuristics::words($node, ['matches' => 'none']));
    }

    /**
     * @test
     */
    public function it_get_a_integer_words_param()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $this->assertTrue(Heuristics::words($node, ['words' => 10]));
        $this->assertFalse(Heuristics::words($node, ['words' => 50]));
    }

    /**
     * @test
     */
    public function it_gets_a_words_as_a_string()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args = ['matches' => 'all', 'words' => 'here content node top'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 'all', 'words' => 'here content banana'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 'any', 'words' => 'here'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 'any', 'words' => 'banana'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 'none', 'words' => 'banana sandwich'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 'none', 'words' => 'banana sandwich here'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 0, 'words' => 'banana sandwich'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 0, 'words' => 'banana sandwich here'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 2, 'words' => 'here content banana'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 3, 'words' => 'here content banana'];
        $this->assertFalse(Heuristics::words($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_a_words_as_a_string_and_is_case_sensitive()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args = ['case_sensitive' => true, 'matches' => 'all', 'words' => 'Here content Node top'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 'all', 'words' => 'Here content banana'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 'any', 'words' => 'Here'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 'any', 'words' => 'here'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 'none', 'words' => 'banana sandwich'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 'none', 'words' => 'Here node'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 0, 'words' => 'here banana'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 0, 'words' => 'Node banana'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 2, 'words' => 'Here content banana'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['case_sensitive' => true, 'matches' => 3, 'words' => 'Here content banana'];
        $this->assertFalse(Heuristics::words($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_regex_words_as_a_string()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args = ['regex' => true, 'matches' => 'all', 'words' => '/he/ /content/'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 'all', 'words' => '/he/ /content/ /banana/'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 'any', 'words' => '/he/'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 'any', 'words' => '/banana/'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 'none', 'words' => '/banana/'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 'none', 'words' => '/he/ /banana/'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 0, 'words' => '/banana/'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 0, 'words' => '/he/'];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 2, 'words' => '/he/ /op/'];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 4, 'words' => '/he/ /banana/'];
        $this->assertFalse(Heuristics::words($node, $args));

        /**
         * Strings with multiple regular expressions should be avoided if your
         * regular expressions have spaces in them.
         *
         * /the banana/ is NOT in our sample data!
         */
        $args = ['matches' => 'none', 'words' => '/the banana/'];
        $this->assertTrue(Heuristics::words($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_array_of_words()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args = ['matches' => 'all', 'words' => explode(" ", 'here content node top')];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 'all', 'words' => explode(" ", 'here content banana')];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 'any', 'words' => ['here']];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 'any', 'words' => ['banana']];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 'none', 'words' => explode(" ", 'banana sandwich')];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 'none', 'words' => explode(" ", 'banana sandwich here')];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 0, 'words' => explode(" ", 'banana sandwich')];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 0, 'words' => explode(" ", 'banana sandwich here')];
        $this->assertFalse(Heuristics::words($node, $args));

        $args = ['matches' => 2, 'words' => explode(" ", 'here content banana')];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['matches' => 3, 'words' => explode(" ", 'here content banana')];
        $this->assertFalse(Heuristics::words($node, $args));

        // some regular expressions
        $args = ['regex' => true, 'matches' => 2, 'words' => explode(" ", '/he/ /op/')];
        $this->assertTrue(Heuristics::words($node, $args));

        $args = ['regex' => true, 'matches' => 3, 'words' => explode(" ", '/he/ /on/ /banana/')];
        $this->assertFalse(Heuristics::words($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_assoc_array_of_words_that_all_match_occurrences_required()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $words = ["/he/" => 6, "/content/" => 2];
        $args = ['regex' => true, 'matches' => 'all', 'words' => $words];
        $this->assertTrue(Heuristics::words($node, $args));

        $words = ['/he/' => 6, '/content/' => 3];
        $args = ['regex' => true, 'matches' => 'all', 'words' => $words];
        $this->assertFalse(Heuristics::words($node, $args));

        $words = ['/he/' => 3, '/banana/' => 1];
        $args = ['regex' => true, 'matches' => 'any', 'words' => $words];
        $this->assertTrue(Heuristics::words($node, $args));

        $words = ['/banana/' => '1'];
        $args = ['regex' => true, 'matches' => 'any', 'words' => $words];
        $this->assertFalse(Heuristics::words($node, $args));

        $words = ['/banana/' => 1];
        $args = ['regex' => true, 'matches' => 'none', 'words' => $words];
        $this->assertTrue(Heuristics::words($node, $args));

        $words = ['/he/' => 1, '/banana/' => 1];
        $args = ['regex' => true, 'matches' => 'none', 'words' => $words];
        $this->assertFalse(Heuristics::words($node, $args));

        $words = ['/banana/' => 1, '/sandwich/' => 1];
        $args = ['regex' => true, 'matches' => 0, 'words' => $words];
        $this->assertTrue(Heuristics::words($node, $args));

        $words = ['/he/', '/banana/'];
        $args = ['regex' => true, 'matches' => 0, 'words' => $words];
        $this->assertFalse(Heuristics::words($node, $args));

        $words = ['/he/' => 5, '/op/' => 1, '/banana/' => 1];
        $args = ['regex' => true, 'matches' => 2, 'words' => $words];
        $this->assertTrue(Heuristics::words($node, $args));

        $words = ['/he/' => 1, '/on/' => 2, '/banana/' => 1, '/sandwich/' => 1];
        $args = ['regex' => true, 'matches' => 3, 'words' => $words];
        $this->assertFalse(Heuristics::words($node, $args));
    }

}