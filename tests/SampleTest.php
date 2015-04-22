<?php
include __DIR__.'/../vendor/autoload.php';

/**
 * Class JsonTest
 *
 * class will use reset() method more heavily than regular practice as we don't want to create tests that screw with
 * one another.
 */
class SampleTest extends PHPUnit_Framework_TestCase
{

    public $sampleObject;

    /**
     * constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->sampleObject = new stdClass();
    }

    /** @test */
    public function it_instantiates_crawler()
    {
        $this->assertTrue(is_object($this->sampleObject));
    }
}