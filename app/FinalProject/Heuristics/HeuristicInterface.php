<?php
namespace FinalProject\Heuristics;
use FinalProject\Support\Articrawl;

/**
 * Some general rules for our Heuristics to abide by.
 *
 * @package FinalProject\Heuristics
 */
interface HeuristicInterface {

    public function score(Articrawl $node);

}