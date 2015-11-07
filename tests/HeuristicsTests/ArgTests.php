<?php

namespace AiCrawlerTests\HeuristicsTests;

use AiCrawlerTests\HeuristicsTestCase;
use Dan\AiCrawler\AiCrawler;
use Dan\AiCrawler\Heuristics;

class ArgTestsHeuristics extends Heuristics
{

    /**
     * Overload characters function to test args() use cases.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return string
     */
    public static function characters(AiCrawler &$node, $args = [])
    {
        $test = debug_backtrace()[1]['function'];

        switch ($test) {
            case "it_gets_one_of_the_params_in_the_args_list":
            case "it_gets_default_method_property_because_no_such_param_exists_in_the_args_list":
                return self::arg($args, 'characters');
            case "it_gets_default_global_property_because_no_such_method_property_exists":
                return self::arg($args, 'matches');
            case "it_throws_an_exception_if_there_is_no_global_property_to_fall_back_on":
                return self::arg($args, 'banana');
            case "it_gets_a_property_that_is_an_boolean":
                return self::boolean($args, 'case_sensitive');
        }

        return "I don't know what to do with that test.";
    }

    /**
     * Overload attributes to test arr() use cases.
     *
     * @param AiCrawler $node
     * @param array $args
     *
     * @return array
     */
    public static function attributes(AiCrawler &$node, $args = [])
    {
        $test = debug_backtrace()[1]['function'];

        switch ($test) {
            case "it_gets_a_property_that_is_an_array":
                return self::arr($args, 'attributes');
        }
    }
}

class ArgTests extends HeuristicsTestCase
{

    /**
     * @test
     */
    public function it_gets_one_of_the_params_in_the_args_list()
    {
        $args = ['characters' => 'args has param'];
        $param = ArgTestsHeuristics::characters(new AiCrawler(), $args);
        $this->assertEquals("args has param", $param);
    }

    /**
     * @test
     */
    public function it_gets_default_method_property_because_no_such_param_exists_in_the_args_list()
    {
        $param = ArgTestsHeuristics::characters(new AiCrawler(), []);
        $this->assertTrue($param);
    }

    /**
     * @test
     */
    public function it_gets_default_global_property_because_no_such_method_property_exists()
    {
        $param = ArgTestsHeuristics::characters(new AiCrawler(), []);
        $this->assertEquals("any", $param);
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function it_throws_an_exception_if_there_is_no_global_property_to_fall_back_on()
    {
        $param = ArgTestsHeuristics::characters(new AiCrawler(), []);
        $this->assertEquals("any", $param);
    }

    /**
     * @test
     */
    public function it_gets_a_property_that_is_an_array()
    {
        $param = ArgTestsHeuristics::attributes(new AiCrawler(), []);
        $this->assertContains("id", $param);
    }

    /**
     * @test
     */
    public function it_gets_a_property_that_is_an_boolean()
    {
        $param = ArgTestsHeuristics::characters(new AiCrawler(), []);
        $this->assertFalse($param);
    }

}