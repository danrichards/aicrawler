<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\Heuristics;
use Dan\Core\Helpers\RegEx;

/**
 * Class CharactersTests
 *
 * @package AiCrawlerTests\HeuristicsTests
 */
class CharactersTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_gets_no_parms() {
        $node = $this->crawler->filter('div[class="entry-content"]');
        $this->assertTrue(Heuristics::characters($node));

        $node = $this->crawler->filter('div[id="content_start"]');
        $this->assertFalse(Heuristics::characters($node));
    }

    /**
     * @test
     */
    public function it_gets_a_node_with_no_content()
    {
        $node = $this->crawler->filter('div[id="content_start"]');
        $this->assertFalse(Heuristics::characters($node, ['matches' => 'any']));
        $this->assertFalse(Heuristics::characters($node, ['matches' => 'all']));
        $this->assertFalse(Heuristics::characters($node, ['matches' => 1]));
        $this->assertTrue(Heuristics::characters($node, ['matches' => 0]));
        $this->assertTrue(Heuristics::characters($node, ['matches' => 'none']));
    }

    /**
     * @test
     */
    public function it_gets_a_integer_characters_param() {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $this->assertTrue(Heuristics::characters($node, ['characters' => 50]));
        $this->assertFalse(Heuristics::characters($node, ['characters' => 200]));
    }

    /**
     * @test
     */
    public function it_gets_a_string_characters_param()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args = ['matches' => 'all', 'characters' => ' .abcdefhimnoprsty'];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 'all', 'characters' => 'abcdefg'];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 'any', 'characters' => 'abcdefg'];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 'any', 'characters' => '|'];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 'none', 'characters' => '|'];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 'none', 'characters' => 'abcdefg'];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 0, 'characters' => '|'];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 0, 'characters' => 'abcdefg'];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 1, 'characters' => 'a'];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 1, 'characters' => '|'];
        $this->assertFalse(Heuristics::characters($node, $args));
    }

    public function it_gets_a_string_characters_params_and_is_case_sensitive()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $args = ['matches' => 'all', 'characters' => ' .Habcdefhimnoprsty'];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 'all', 'characters' => ' .abcdefhimnoprsty'];
        $this->assertFalse(Heuristics::characters($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_numeric_array_of_characters_to_match()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $all = str_split(' .abcdefhimnoprsty');
        $abcdefg = str_split('abcdefg');

        $args = ['matches' => 'all', 'characters' => $all];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 'all', 'characters' => $abcdefg];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 'any', 'characters' => $abcdefg];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 'any', 'characters' => ['|']];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 'none', 'characters' => ['|']];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 'none', 'characters' => $abcdefg];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 0, 'characters' => ['|']];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 0, 'characters' => $abcdefg];
        $this->assertFalse(Heuristics::characters($node, $args));

        $args = ['matches' => 1, 'characters' => ['a']];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 1, 'characters' => ['|']];
        $this->assertFalse(Heuristics::characters($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_assoc_array_of_characters_that_all_match_occurrences_required()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        // Current character frequency in this node (exactly)
        $characters = [
            ' ' => 21, '.' => 2, 'a' => 2, 'b' => 1, 'c' => 2,  'd' => 2,
            'e' => 16, 'f' => 2, 'h' => 6, 'i' => 2, 'm' => 1,  'n' => 10,
            'o' => 11, 'p' => 1, 'r' => 4, 's' => 4, 't' => 13, 'y' => 2
        ];

        $args = ['matches' => 'all', 'characters' => $characters];
        $this->assertTrue(Heuristics::characters($node, $args));

        // Require one more e than we have.
        $characters['e'] = 17;
        $args = ['matches' => 'all', 'characters' => $characters];
        $this->assertFalse(Heuristics::characters($node, $args));

        // No specification for e has been made.
        $characters = array_diff_key($characters, ['e' => null]);
        $args = ['matches' => 'all', 'characters' => $characters];
        $this->assertTrue(Heuristics::characters($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_assoc_array_of_characters_that_have_any_matching_occurrences_required()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        $characters = ['e' => 1, '|' => 1];
        $args = ['matches' => 'any', 'characters' => $characters];
        $this->assertTrue(Heuristics::characters($node, $args));

        $characters = ['e' => 17, '|' => 1];
        $args = ['matches' => 'any', 'characters' => $characters];
        $this->assertFalse(Heuristics::characters($node, $args));

        $characters = ['|' => 1];
        $args = ['matches' => 'any', 'characters' => $characters];
        $this->assertFalse(Heuristics::characters($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_assoc_array_of_characters_that_have_at_least_n_matches_occurrences_required()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        // Current character frequency in this node (exactly)
        $characters = [
            ' ' => 21, '.' => 2, 'a' => 2, 'b' => 1, 'c' => 2,  'd' => 2,
            'e' => 99, 'f' => 9, 'h' => 9, 'i' => 9, 'm' => 9,  'n' => 9
        ];

        $args = ['matches' => 7, 'characters' => $characters];
        $this->assertTrue(Heuristics::characters($node, $args));

        $args = ['matches' => 8, 'characters' => $characters];
        $this->assertFalse(Heuristics::characters($node, $args));
    }

    /**
     * @test
     */
    public function it_gets_assoc_array_of_characters_that_have_no_matches_occurrences_required()
    {
        $node = $this->crawler->filter('div[class="entry-content"]');

        // Current character frequency in this node (exactly)
        $characters = ['|' => 1];
        $args = ['matches' => 'none', 'characters' => $characters];
        $this->assertTrue(Heuristics::characters($node, $args));

        $characters['e'] = 16;
        $args = ['matches' => 'none', 'characters' => $characters];
        $this->assertFalse(Heuristics::characters($node, $args));
    }

}