<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;

/**
 * Class sentencesTests
 */
class SentencesTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_gets_no_sentences()
    {

    }

    /**
     * @test
     */
//    public function it_get_a_integer_sentences_param()
//    {
//        $node = $this->crawler->filter('div[class="entry-content"]');
//
//        $this->assertTrue(Heuristics::sentences($node, ['sentences' => 2]));
//        $this->assertFalse(Heuristics::sentences($node, ['sentences' => 3]));
//    }

    /**
     * @test
     */
//    public function it_gets_a_sentences_as_a_string()
//    {
//        $node = $this->crawler->filter('div[class="entry-content"]');
//
//        $args = ['matches' => 'all', 'sentences' => 'here content node top'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'all', 'sentences' => 'here content banana'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'any', 'sentences' => 'here'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'any', 'sentences' => 'banana'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'none', 'sentences' => 'banana sandwich'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'none', 'sentences' => 'banana sandwich here'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 0, 'sentences' => 'banana sandwich'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 0, 'sentences' => 'banana sandwich here'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 2, 'sentences' => 'here content banana'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 3, 'sentences' => 'here content banana'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//    }
//
//    /**
//     * @test
//     */
//    public function it_gets_a_sentences_as_a_string_and_is_case_sensitive()
//    {
//        $node = $this->crawler->filter('div[class="entry-content"]');
//
//        $args = ['case_sensitive' => true, 'matches' => 'all', 'sentences' => 'Here content Node top'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 'all', 'sentences' => 'Here content banana'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 'any', 'sentences' => 'Here'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 'any', 'sentences' => 'here'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 'none', 'sentences' => 'banana sandwich'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 'none', 'sentences' => 'Here node'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 0, 'sentences' => 'here banana'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 0, 'sentences' => 'Node banana'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 2, 'sentences' => 'Here content banana'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['case_sensitive' => true, 'matches' => 3, 'sentences' => 'Here content banana'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//    }
//
//    /**
//     * @test
//     */
//    public function it_gets_regex_sentences_as_a_string()
//    {
//        $node = $this->crawler->filter('div[class="entry-content"]');
//
//        $args = ['regex' => true, 'matches' => 'all', 'sentences' => '/he/ /content/'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 'all', 'sentences' => '/he/ /content/ /banana/'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 'any', 'sentences' => '/he/'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 'any', 'sentences' => '/banana/'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 'none', 'sentences' => '/banana/'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 'none', 'sentences' => '/he/ /banana/'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 0, 'sentences' => '/banana/'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 0, 'sentences' => '/he/'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 2, 'sentences' => '/he/ /op/'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 4, 'sentences' => '/he/ /banana/'];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        /**
//         * Strings with multiple regular expressions should be avoided if your
//         * regular expressions have spaces in them.
//         *
//         * /the banana/ is NOT in our sample data!
//         */
//        $args = ['matches' => 'none', 'sentences' => '/the banana/'];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//    }
//
//    /**
//     * @test
//     */
//    public function it_gets_array_of_sentences()
//    {
//        $node = $this->crawler->filter('div[class="entry-content"]');
//
//        $args = ['matches' => 'all', 'sentences' => explode(" ", 'here content node top')];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'all', 'sentences' => explode(" ", 'here content banana')];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'any', 'sentences' => ['here']];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'any', 'sentences' => ['banana']];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'none', 'sentences' => explode(" ", 'banana sandwich')];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 'none', 'sentences' => explode(" ", 'banana sandwich here')];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 0, 'sentences' => explode(" ", 'banana sandwich')];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 0, 'sentences' => explode(" ", 'banana sandwich here')];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 2, 'sentences' => explode(" ", 'here content banana')];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['matches' => 3, 'sentences' => explode(" ", 'here content banana')];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        // some regular expressions
//        $args = ['regex' => true, 'matches' => 2, 'sentences' => explode(" ", '/he/ /op/')];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $args = ['regex' => true, 'matches' => 3, 'sentences' => explode(" ", '/he/ /on/ /banana/')];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//    }
//
//    /**
//     * @test
//     */
//    public function it_gets_assoc_array_of_sentences_that_all_match_occurrences_required()
//    {
//        $node = $this->crawler->filter('div[class="entry-content"]');
//
//        $sentences = ["/he/" => 6, "/content/" => 2];
//        $args = ['regex' => true, 'matches' => 'all', 'sentences' => $sentences];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $sentences = ['/he/' => 6, '/content/' => 3];
//        $args = ['regex' => true, 'matches' => 'all', 'sentences' => $sentences];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $sentences = ['/he/' => 3, '/banana/' => 1];
//        $args = ['regex' => true, 'matches' => 'any', 'sentences' => $sentences];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $sentences = ['/banana/' => '1'];
//        $args = ['regex' => true, 'matches' => 'any', 'sentences' => $sentences];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $sentences = ['/banana/' => 1];
//        $args = ['regex' => true, 'matches' => 'none', 'sentences' => $sentences];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $sentences = ['/he/' => 1, '/banana/' => 1];
//        $args = ['regex' => true, 'matches' => 'none', 'sentences' => $sentences];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $sentences = ['/banana/' => 1, '/sandwich/' => 1];
//        $args = ['regex' => true, 'matches' => 0, 'sentences' => $sentences];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $sentences = ['/he/', '/banana/'];
//        $args = ['regex' => true, 'matches' => 0, 'sentences' => $sentences];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//
//        $sentences = ['/he/' => 5, '/op/' => 1, '/banana/' => 1];
//        $args = ['regex' => true, 'matches' => 2, 'sentences' => $sentences];
//        $this->assertTrue(Heuristics::sentences($node, $args));
//
//        $sentences = ['/he/' => 1, '/on/' => 2, '/banana/' => 1, '/sandwich/' => 1];
//        $args = ['regex' => true, 'matches' => 3, 'sentences' => $sentences];
//        $this->assertFalse(Heuristics::sentences($node, $args));
//    }

}